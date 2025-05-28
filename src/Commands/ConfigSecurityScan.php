<?php

namespace Elohim\LaravelSecurityCheck\Commands;

use Illuminate\Console\Command;

class ConfigSecurityScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config-security:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Valida a configuração de segurança do projeto Laravel.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("✅ Iniciando validação de segurança do projeto Laravel, que ajudam a garantir que o ambiente de produção esteja seguro e otimizado.");
        
        $this->info("\n✅ Verificando dependências desatualizadas");
        $this->scanDependencies();

        $this->info("✅ Verificando .env ");

        $env = base_path('.env');
        if (!file_exists($env)) {
            $this->error(".env não encontrado.");
            return 1;
        }

        $content = file_get_contents($env);

        if (str_contains($content, 'APP_DEBUG=true')) {
            $this->warn("⚠️ APP_DEBUG=true -> Defina como false em produção.");
        }

        if (str_contains($content, 'APP_ENV=local')) {
            $this->warn("⚠️ APP_ENV=local -> Use production em produção.");
        }

        if (!preg_match('/^APP_KEY=\\S+/m', $content)) {
            $this->warn("⚠️ APP_KEY ausente ou inválida. Execute php artisan key:generate.");
        }

        $this->scanDebugbar();
        $this->scanTelescope();
        $this->scanAppURL();
        $this->scanCache();
        $this->scanQueue();
        $this->scanSession();
        $this->scanCSRF();
        $this->scanMailMailer();
        $this->scanConfigRoutes();
        $this->info("\n✅ Verificação de configuração .env concluída.");

        $this->info("\n✅ Verificando permissões de pastas e arquivos");

        foreach (['storage', 'bootstrap/cache'] as $dir) {
            $path = base_path($dir);
            $perms = substr(sprintf('%o', fileperms($path)), -3);
            if ($perms !== '755') {
                $this->warn("Permissões incorretas em $dir (atual: $perms, esperado: 755)");
            }
        }

        $this->info("\n== Verificando headers HTTP ==");

        $url = env('APP_URL', 'http://localhost');
        $headers = @get_headers($url, 1);

        $required = [
            'X-Frame-Options',
            'X-Content-Type-Options',
            'Strict-Transport-Security',
            'Referrer-Policy',
            'Content-Security-Policy',
        ];

        foreach ($required as $header) {
            if (!isset($headers[$header])) {
                $this->warn("Header ausente: $header");
            }
        }

        $this->info("\n✅ Validação de segurança finalizada.");
        return 0;
    }

    /**
     * Scan for outdated dependencies using Composer.
     */
    public function scanDependencies() 
    {
        $output = shell_exec('composer outdated --direct --format=json');
        $data = json_decode($output, true);

        if (empty($data['installed'])) {
            $this->info("✅ Todas as dependências estão atualizadas.");
        } else {
            $this->warn("⚠️ Dependências desatualizadas encontradas:");
            foreach ($data['installed'] as $package) {
                $this->warn("{$package['name']}: {$package['version']} -> {$package['latest']}");
            }
        }
    }

    /**
     * Scan for Debugbar configuration.
     * Warn if Debugbar is enabled in production.
     */
    public function scanDebugbar() 
    {
        $this->info("✅ Verificando Debugbar...");

        if (class_exists(\Debugbar::class) || env('DEBUGBAR_ENABLED', true)) {
            $this->warn("⚠️ Debugbar está habilitado. Desative em produção.");
        } else {
            $this->info("✅ Debugbar está desativado.");
        }        
    }

    /**
     * Scan for Telescope configuration.
     * Warn if Telescope is enabled in production.
     */
    public function scanTelescope() 
    {
        $this->info("✅ Verificando Telescope...");

        if (class_exists(\Laravel\Telescope\Telescope::class) || env('TELESCOPE_ENABLED', true)) {
            $this->warn("⚠️ Telescope está habilitado. Desative em produção.");
        } else {
            $this->info("✅ Telescope está desativado.");
        }
    }

    /**
     * Scan for APP_URL configuration.
     * Warn if APP_URL is not set or uses localhost.
     */
    public function scanAppURL() 
    {
        $this->info("✅ Verificando APP_URL...");

        $appUrl = env('APP_URL');
        if (!$appUrl) {
            $this->warn("⚠️ APP_URL não está definido. Defina corretamente em .env.");
        } elseif (!filter_var($appUrl, FILTER_VALIDATE_URL)) {
            $this->warn("⚠️ APP_URL inválido: $appUrl");
        } else {
            $this->info("✅ APP_URL está definido corretamente: $appUrl");
        }

        if (preg_match('/^APP_URL=(http:\/\/localhost|http:\/\/127\.0\.0\.1)/m', $appUrl)) {
            $this->warn("⚠️ APP_URL está usando localhost. Configure o domínio de produção.");
        }
    }
    
    /**
     * Scan for Cache configuration.
     * Warn if CACHE_DRIVER is set to file or array in production.
     */
    public function scanCache() 
    {
        $this->info("✅ Verificando Cache...");

        $cacheDriver = env('CACHE_DRIVER', 'file');
        
        if (preg_match('/^CACHE_DRIVER=(file|array)/m', $cacheDriver)) {
            $this->warn("⚠️ CACHE_DRIVER não recomendado para produção.");
        }
        if (preg_match('/^CACHE_DRIVER=(redis|memcached)/m', $cacheDriver)) {
            $this->info("✅ CACHE_DRIVER está configurado corretamente: $cacheDriver");
        }
    }

    /**
     * Scan for Queue configuration.
     * Warn if QUEUE_CONNECTION is set to sync or null in production.
     */
    public function scanQueue() 
    {
        $this->info("✅ Verificando Queue...");

        $queueDriver = env('QUEUE_CONNECTION', 'sync');
        
        if (preg_match('/^QUEUE_CONNECTION=(sync|null)/m', $queueDriver)) {
            $this->warn("⚠️ QUEUE_CONNECTION não recomendado para produção.");
        }
        if (preg_match('/^QUEUE_CONNECTION=(redis|database|beanstalkd)/m', $queueDriver)) {
            $this->info("✅ QUEUE_CONNECTION está configurado corretamente: $queueDriver");
        }
    }

    /**
     * Scan for Session configuration.
     * Warn if SESSION_DRIVER is set to file or array in production.
     */
    public function scanSession() 
    {
        $this->info("✅ Verificando Session...");

        $sessionDriver = env('SESSION_DRIVER', 'file');
        
        if (preg_match('/^SESSION_DRIVER=(file|array)/m', $sessionDriver)) {
            $this->warn("⚠️ SESSION_DRIVER não recomendado para produção.");
        }
        if (preg_match('/^SESSION_DRIVER=(redis|database|cookie)/m', $sessionDriver)) {
            $this->info("✅ SESSION_DRIVER está configurado corretamente: $sessionDriver");
        }
    }

    /**
     * Scan for CSRF configuration.
     * Warn if session.secure_cookie or session.same_site are not set.
     */
    public function scanCSRF() 
    {
        $this->info("✅ Verificando CSRF...");

        if (!config('session.secure_cookie')) {
            $this->warn("⚠️ session.secure_cookie não está habilitado. Habilite para segurança adicional.");
        } else {
            $this->info("✅ session.secure_cookie está habilitado.");
        }

        if (!config('session.same_site')) {
            $this->warn("⚠️ session.same_site não está configurado. Configure para 'strict' ou 'lax'.");
        } else {
            $this->info("✅ session.same_site está configurado: " . config('session.same_site'));
        }
    }

    /**
     * Scan for Mail Mailer configuration.
     * Warn if MAIL_MAILER is set to smtp or sendmail in production.
     */
    public function scanMailMailer() 
    {
        $this->info("✅ Verificando Mail Mailer...");

        $mailDriver = env('MAIL_MAILER', 'smtp');
        
        if (preg_match('/^MAIL_MAILER=(smtp|sendmail)/m', $mailDriver)) {
            $this->info("✅ MAIL_MAILER está configurado corretamente: $mailDriver");
        } else {
            $this->warn("⚠️ MAIL_MAILER não recomendado para produção: $mailDriver");
        }
    }
    
    /**
     * Scan for Config and Route cache.
     * Warn if config or route cache is not generated.
     */
    public function scanConfigRoutes() 
    {
        $this->info("✅ Verificando Config Routes...");
        
        if (!file_exists(base_path('bootstrap/cache/config.php'))) {
            $this->warn("⚠️ Config cache não gerado. Execute: php artisan config:cache");
        }
        if (!file_exists(base_path('bootstrap/cache/routes-v7.php')) && !file_exists(base_path('bootstrap/cache/routes.php'))) {
            $this->warn("⚠️ Route cache não gerado. Execute: php artisan route:cache");
        }
    }
}