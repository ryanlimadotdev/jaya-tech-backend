# Jaya tech
## Teste técnico de processo seletivo (backend PHP)
Tecnologias usadas:

- PHP 8.3 (com lib Swoole 5.1.2)
- Hyperf 3.1
- MySQL

### Getting started
_Confira os volumes_

#### Inicie o servidor (porta padrão do framework: 9501):
```bash
cp .env.example .env
docker-compose build
docker-compose up -d
docker exec -it hyperf bash
composer install -o
php bin/hyperf.php migrate
php bin/hyperf.php start
```

#### Realize a suíte de testes:
```bash
docker exec -it hyperf bash
composer test
```
#### Documentação no Swagger no uri raiz ([http://localhost:9501/]())

### Decisões técnicas
- A escolha de uma framework baseado em Swoole para tornar o serviço mais eficiente aproveitando o servidor assíncrono do Swoole
- A não utilização do padrão Active record (padrão do framework) para proteger o domínio e evitar acoplamento.
- Utilização do MySql pela integração mais madura com e estável com Swoole
- Disponibilização estática do Swagger
- Attributes para documentar os controllers e gerar automaticamente a documentação da API, apesar de "poluir os controllers", em se tratando que uma documentação e de que os Attributes de hyperf/swagger são compatíveis com os padrões do Swagger framework-agnostic

#### Dicas
- Caso utilize o PHPStorm, recomendo marcar o diretório _runtime_ como excluded, pois os arquivos de proxy tem o mesmo nome de classe que as originais.
- Como é uma aplicação de PHP Cli e não FPM, o debbuging será diferente do convencional. Recomendo a utilização da biblioteca spatie/ray e alguma image do [Buggregator](https://github.com/buggregator/laravel-app) para tal.

### Next steps
- Após a criação da pasta vendor, o entrypoint do container do hyperf pode ser substituído para "php bin/hyperf.php start" ou "php bin/hyperf.php server:watch" para o modo com hot reload.
