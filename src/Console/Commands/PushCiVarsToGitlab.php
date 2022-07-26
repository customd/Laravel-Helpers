<?php

namespace CustomD\LaravelHelpers\Console\Commands;


use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class PushCiVarsToGitlab extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:ci {--I|interactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy to CI Pipeline';

    protected array $ignore = [
        'GITLAB_PROJECT_ID',
        'GITLAB_ENV_TOKEN',
        'APP_DEBUG',
        'APP_ENV',
        'LOG_DEPRECATIONS_CHANNEL',
        'LOG_LEVEL',
        'BROADCAST_DRIVER',
        'MEMCACHED_HOST',
        'REDIS_PASSWORD',
        'REDIS_PORT',
        'PUSHER_APP_ID',
        'PUSHER_APP_KEY',
        'PUSHER_APP_SECRET',
        'PUSHER_APP_CLUSTER',
        'MIX_PUSHER_APP_KEY',
        'MIX_PUSHER_APP_CLUSTER',
    ];

    protected array $extras = [
        'STAGING_ENABLED'             => 1,
        'STAGING_ADDITIONAL_HOSTS'    => '',
        'PRODUCTION_REPLICAS'         => 2,
        'PRODUCTION_ADDITIONAL_HOSTS' => '',
        'DB_INITIALIZE'               => "/cnb/lifecycle/launcher 'php artisan migrate:fresh --seed --force --no-interaction'",
        'DB_MIGRATE'                  => "/cnb/lifecycle/launcher php artisan migrate --force --no-interaction",
        "APP_KEY"                     => "",
        "GITLAB_TOKEN"                => '',
        "SEMANTIC_RELEASE_PACKAGE"    => '',
        "SLACK_WEBHOOK"               => 'https://hooks.slack.com/services/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',
        "HELM_UPGRADE_VALUES_FILE"    => '.gitlab/auto-deploy-values.yaml'
    ];

    protected string $projectId;
    protected string $authToken;
    protected string $gitDomain;
    protected string $baseUri;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->projectId = env("GITLAB_PROJECT_ID") ??  $this->ask("Project Id");
        $this->authToken = env("GITLAB_ENV_TOKEN") ??  $this->ask('Gitlab Token');
        $this->gitDomain = env("GITLAB_DOMAIN") ??  $this->ask('Gitlab Domain', 'https://git.customd.com');

        $this->baseUri = $this->gitDomain . '/api/v4/projects/' . $this->projectId;

        $this->extras['GITLAB_TOKEN'] = $this->authToken;
        $this->extras['APP_KEY'] = env('APP_KEY');

        collect(\Dotenv\Dotenv::createArrayBacked(base_path())->load())
            ->filter(fn ($v, $k) => ! in_array($k, $this->ignore))
            ->each(fn ($v, $k) => $this->setCustomValue('K8S_SECRET_' . $k, $v));

        $this->setCustomValue('K8S_SECRET_REDIS_PREFIX', $this->projectId, true);

        collect($this->extras)->each(fn ($v, $k) => $this->putVar($k, '*', $v));

        return Command::SUCCESS;
    }

    /**
     * @param mixed $v
     */
    protected function setCustomValue(string $k, $v = null, bool $append = false): void
    {
        $choice = $this->choice(
            "Set {$k} for: ",
            ["none", "*", "production", "staging", "testing"],
            "1,2,3",
            null,
            true
        );
        if ($choice[0] === 'none') {
            return;
        }
        collect($choice)->each(fn ($c) => $this->putVar($k, $c, $append ? $v . $c : $v));
    }

    /**
     * @param mixed $default
     */
    protected function putVar(string $key, string $env, $default = null): void
    {
        $value = $this->ask($key . " on " . $env, $default ?? env($key));

        if ($value === '') {
            return;
        }

        try {
            $res = Http::withHeaders([
                "PRIVATE-TOKEN" => $this->authToken
            ])->post($this->baseUri . '/variables', [
                "key"               => $key,
                "environment_scope" => $env,
                "value"             => $value
            ])->throw();
            $this->info($key . " added");
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->error($e->getMessage());
        }
    }
}

