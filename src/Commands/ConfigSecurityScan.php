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
    protected $signature = 'configsecurity:scan';

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
        $this->info("✅ Iniciando validação de segurança do projeto Laravel");
        
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

        $this->info("\n== Verificando permissões de pastas ==");

        foreach (['storage', 'bootstrap/cache'] as $dir) {
            $path = base_path($dir);
            $perms = substr(sprintf('%o', fileperms($path)), -3);
            if ($perms !== '755') {
                $this->warn("Permissões incorretas em $dir (atual: $perms, esperado: 755)");
            }
        }

        $this->info("\n== Verificando headers HTTP ==");

        $url = 'http://unifesp-app.test:8000/';
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
    public function scanDependencies() {
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
}
