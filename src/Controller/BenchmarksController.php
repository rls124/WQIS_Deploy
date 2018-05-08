<?php

	namespace App\Controller;

	use App\Controller\AppController;

	/**
	 * Benchmarks Controller
	 *
	 * @property \App\Model\Table\BenchmarksTable $Benchmarks
	 *
	 * @method \App\Model\Entity\Benchmark[] paginate($object = null, array $settings = [])
	 */
	class BenchmarksController extends AppController {

		public function measurementbenchmarks() {
			$Benchmarks = $this->Benchmarks->find('all');
			$this->set(compact('Benchmarks'));
		}

		public function updatefield() {
			$this->render(false);

			//Ensure that measure is in POST data
			if (!$this->request->getData('measure')) {
				return;
			}
			$measure = $this->request->getData('measure');

			//Get the benchmark we are editing
			$benchmark = $this->Benchmarks
				->find('all')
				->where(['Measure = ' => $measure])
				->first();
			$parameter = $this->request->getData('parameter');
			$value = $this->request->getData('value');
			//Set the edited field
			$benchmark->$parameter = $value;
			//Save changes
			$this->Benchmarks->save($benchmark);
		}

	}
