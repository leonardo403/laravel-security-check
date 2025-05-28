# Laravel Security Check

Este projeto tem como objetivo fornecer ferramentas e práticas recomendadas para verificar e melhorar a segurança de aplicações Laravel.

## Funcionalidades

- Verificação de configurações inseguras
- Análise de permissões de arquivos e diretórios

## Sugestões de melhorias de segurança
1. Debugbar e Telescope
Certifique-se de que pacotes como barryvdh/laravel-debugbar e laravel/telescope não estejam habilitados em produção.

2. APP_URL
Verifique se APP_URL está corretamente configurado para o domínio de produção.

3. APP_KEY
Já está validando, mas também pode checar se não é a chave padrão (base64:... sem alteração).

4. Queue e Cache Drivers
Evite usar drivers como sync ou file em produção para QUEUE_CONNECTION e CACHE_DRIVER.

5. Session Driver
Evite SESSION_DRIVER=file em produção, prefira redis ou database.

6. Mail Driver
Evite MAIL_MAILER=log ou MAIL_MAILER=array em produção.

7. Trusted Proxies
Verifique se TRUSTED_PROXIES está configurado se estiver atrás de proxy/reverse proxy.

8. CORS
Certifique-se de que as configurações de CORS não estejam abertas demais.

9. Logging
Evite LOG_CHANNEL=stack com single em produção, prefira daily ou sistemas externos.

10. Diretórios públicos
Garanta que arquivos sensíveis (como .env, composer.lock, etc.) não estejam acessíveis publicamente.

11. Composer Autoload
Verifique se o autoload está otimizado (composer dump-autoload -o).

12. Config Cache
Verifique se as configurações estão em cache (php artisan config:cache).

13. Route Cache
Verifique se as rotas estão em cache (php artisan route:cache).

14. Debug Mode
Além do APP_DEBUG, certifique-se de que não há outros modos de debug ativos.

15. Exposição de erros
Verifique se APP_DEBUG está false e se não há handlers customizados expondo stack traces.



## Como utilizar clonando o repositório

1. Clone o repositório:
    ```
    git clone https://github.com/leonardo403/laravel-security-check.git
    ```
2. Instale as dependências:
    ```
    composer install
    ```
3. Execute as verificações de segurança:
    ```
    php artisan security:check
    ```

## Utilizar com Composer Install
1. Para instalar o pacote via Composer:
    ```
    composer require elohim/laravel-security-check
    ```

2. Após a instalação, você pode executar o comando de verificação de segurança:
    ```
    php artisan security:check
    ```    

## Requisitos

- PHP >= 8.0
- Composer
- Laravel >= 9.x

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests.

## Licença

Este projeto está licenciado sob a licença MIT.