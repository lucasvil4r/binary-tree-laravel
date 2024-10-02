
# Simulação de Árvore Binária

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/lucasvil4r/binary-tree-laravel.git
```

Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=Árvore Binária
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```

Instalar as dependências do projeto
```sh
docker-compose exec app composer install
```


Gerar a key do projeto Laravel
```sh
docker-compose exec app php artisan key:generate
```

Execute o Migrate
```sh
docker-compose exec app php artisan migrate
```

Acessar o projeto
[http://localhost:8989](http://localhost:8989)
