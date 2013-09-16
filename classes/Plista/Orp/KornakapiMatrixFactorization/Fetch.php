<?php
namespace Plista\Orp\KornakapiMatrixFactorization;


use Plista\Orp\Sdk\Context;
use Plista\Orp\Sdk\Handle;
use Plista\Orp\Sdk\Recs;


/**
 * Kornakapi: Weighted Matrix Factorization
 * this will use matrix factorization to callculate Recommendation.
 * Matrixfactorization aproximates the two matrixes whoes product results in the completed user-item matrix.
 *
 * This is an iterative method, thus the allgroithm is repeated until it converges or stopped.
 * This is only tractable if run in parallel
 *
 *
 * Mathematical Terms
 * - Alternating Least Squares weighted lambda regularization
 *
 * Technology Terms
 * - Collaborative Filtering
 * - Apache Mahout
 * - Kornakapi
 * - Java
 * - HTTP Interface
 *
 * Response Rate on 8 core with 16 gb ram:
 * 	~86 ms
 *
 *
 *
 * ALS for implicit user rating (what is used here)
 * @link http://www2.research.att.com/~yifanhu/PUB/cf.pdf
 * ALS for exolicit rating
 * @link http://www.hpl.hp.com/personal/Robert_Schreiber/papers/2008%20AAIM%20Netflix/netflix_aaim08%28submitted%29.pdf
 *
 * Kornakapi uses mahout, check out this document for some usage of mahout
 * @link https://www.ibm.com/developerworks/java/library/j-mahout/j-mahout-pdf.pdf
 * @link http://kickstarthadoop.blogspot.de/2011/05/generating-recommendations-with-mahout_26.html
 * and here is the api of Mahout
 * @link http://archive.cloudera.com/cdh4/cdh/4/mahout/mahout-core/
 *
 */
class Fetch implements Handle {

	private static $path = '/var/www/log/';

	/**
	 * @var Model
	 */
	private $model;

	private $userid;

	private $itemid;

	private $request;

	private $limit;

	const SCORE = 2;
	const ITEM = 3;

	/**
	 * This class, fetches the recommendations
	 * first if userid is given and the userid is in taste_preferences, we ask kornakapi for recommendations for this user, based on his item history
	 * if there is no userid  or the user is new (not in the database) but an itemid is given we ask kornakapi for recommendations similar to that item.
	 * @return array|Recs
	 * @throws Exception
	 */
	public function fetch() {
	//get the recommendations stored by the worker method
		$res=array();
		$userid = $this->userid;
		$log='';

		$list = $this->model->getRead();
		//check if we have a user id
		if(isset($userid) && $userid != 0){
			//check if user is allready contained in database, so we can give recommendations for user

			if($this->model->userIndb($userid)){
				$res = $list->recommend(
					$this->model->getKornakapi_recommender(),
					'userID',
					array(strval($userid)),
					strval($this->model->getDomainid()),
					$this->model->getLimit()
				);
//				$log.= 'userbased'.serialize($res) . "\n";
				if(empty($res)){
					$log.='empty userbased recommendation for user: '. strval($this->model->getUserid()). "\n";
				}
			}else{
				$log.='user not in db: '. strval($this->model->getUserid()). "\n";
			}

		}else{
			$log.='empty username' . "\n";
		}
		//if we do not have recommendations yet but we have an item id, we try to get item based recommendations
		$itemid= $this->itemid;
		if(empty($res) && isset($itemid)){
			//check if item id is contained in database, so recommendations can be given for the current users item
			if($this->model->itemIndb($itemid)){
					if($itemid > 0){
						$res = $list->recommend(
							$this->model->getKornakapi_recommender(),
							'itemIDs',
							array(strval($this->itemid)),
							strval($this->model->getDomainid()),
							$this->model->getLimit()
						);
						$log.='itembased'. serialize($res) . "\n";
					}
					if(empty($res)){
						$log.='empty itembased recommendation for item: '.strval($itemid). "\n";
					}
			}else{
				$log.='item not in db: '.strval($itemid). "\n";
			}
		}
		if(empty($res) && empty($itemid)){
			$log.= 'No res and empty itemid' . "\n";
		}

		//normalize results, write them in right format and create Recs object
		$recs = array();
		if(!empty($res)){
			$res = $this->normalize($res);
			foreach($res as $index => $result){
				$recs['result'][]= $index;
				if($result < 0.001){
					$recs['score'][]= 0.001;//hotfix
				}
				else{
					$recs['score'][]= $result;
				}
			}
			$recs = new Recs($recs);
		}

		//if nether item based nor user based recommendations are available return most viewed items
		//TODO: Implement Mahout MostPopularItem Recommender
		if(empty($recs)){
			$log.='Returned Empty to: '.strval($userid). "\n";
		}
		file_put_contents( self::$path.'Fetch.log', $log. '------------------------'."\n", FILE_APPEND | LOCK_EX);
		return $recs;

	}

	/**
	 * Method that normalizes the scores of the recommendations by kornakapi
	 * @param array $itemids
	 * @return array
	 */
	private function normalize(array $itemids) {
		if (empty($itemids)) {
			return array();
		}
		$results = array();
		foreach ($itemids as $item) {
			$results[$item['itemID']] = $item['value'];
		}
		$highest = max($results);
		return array_map(function ($x) use ($highest) {
			return ($highest <= 0) ? 0 : $x / $highest;
		}, $results);
	}


	/**
	 * @param $body
	 * @return bool
	 */
	public function validate($body) {
		if(empty($body['context']['simple'][25]) && empty($body['context']['simple'][57])){
			throw new ValidationException('Recommendation requires ether userid or itemid!');
		}
		if(empty($body['limit'])){
			throw new ValidationException('Recommendation requires an item limit!');
		}
	}

	/**
	 * @param $body
	 * @return mixed
	 */
	public function handle($request) {
		$this->request= $request;
		$this->limit = $request['limit'];
		$context = new Context($request['context']);
		$this->itemid =$context->getItem_source();
		$this->userid =$context->getUser_cookie();
		$this->model = new Model($context, $this->limit);
		$this->model->validate();
		return $this->fetch();
	}


}