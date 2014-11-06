<?php

class temposDataloadTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('preset-name', sfCommandArgument::REQUIRED, 'The preset name'),
    ));

    $this->addOptions(array(
			new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Do not ask for confirmation'),
    ));

    $this->namespace        = 'tempos';
    $this->name             = 'data-load';
    $this->briefDescription = 'Load a given data preset';
    $this->detailedDescription = <<<EOF
The [tempos:data-load|INFO] task loads a data preset.

  [./symfony tempos:data-load preset-name|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

		$databaseManager = new sfDatabaseManager($this->configuration);

		$istoptions = array();

		if (in_array('no-confirmation', $options))
		{
			$istoptions[] = 'no-confirmation';
		}

		$presetName = $arguments['preset-name'];
		$fixtureFile = sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.$presetName.'.sql';

		if (!file_exists($fixtureFile))
		{
			$this->logSection('tempos', sprintf('Preset not found: %s', $presetName), 512, 'ERROR');
			return;
		} else
		{
			$this->logSection('tempos', sprintf('Loading preset: %s', $presetName), 512);
		}

		$propelInsertSqlTask = new sfPropelInsertSqlTask($this->dispatcher, $this->formatter);
		$propelInsertSqlTask->run(array(), $istoptions);

		$sql = file_get_contents($fixtureFile);

		$con = Propel::getConnection('propel');
		$stmt = $con->prepare($sql);
		$stmt->execute();
  }
}
