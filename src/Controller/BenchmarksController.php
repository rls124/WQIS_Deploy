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
			$this->loadModel("MeasurementMeta");
			$Benchmarks = $this->MeasurementMeta->find('all');
			$this->set(compact('Benchmarks'));
		}

		public function updatefield() {
			$this->render(false);

			$this->loadModel("MeasurementMeta");

			//Ensure that measure is in POST data
			if (!$this->request->getData('measure')) {
				return;
			}
			$measure = $this->request->getData('measure');

			//Get the benchmark we are editing
			$benchmark = $this->MeasurementMeta
				->find('all')
				->where(['measureKey = ' => $measure])
				->first();
			$parameter = $this->request->getData('parameter');
			$value = $this->request->getData('value');
			//Set the edited field
			$benchmark->$parameter = $value;
			//Save changes
			$this->MeasurementMeta->save($benchmark);
		}
	}