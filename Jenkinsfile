pipeline {
    agent any

    stages {
        stage('passport key copy'){
            steps {
                withCredentials([file(credentialsId: 'TDA_PRIV', variable: 'priv')]) {
                    sh 'cp $priv src/storage/oauth-private.key'
                }
                withCredentials([file(credentialsId: 'TDA_PUB', variable: 'pub')]) {
                    sh 'cp $pub src/storage/oauth-public.key'
                }
            }
        }
        stage('PHP install') {
            steps {
                withCredentials([file(credentialsId: 'TDA_env', variable: 'env')]) {
                    sh 'cp $env src/.env'
                }
                sh 'cd src && composer install --no-dev'
                sh 'cd src && php artisan migrate'
                sh 'cd src && php artisan db:seed'
            }
        }
        stage('deploy') {
            steps {
                sh 'cp /var/www/html/tda-api/storage/logs/laravel.log src/storage/logs/laravel.log'
                sh 'cp -r /var/www/html/tda-api/storage/app src/storage/app'
                sh 'sudo rm -rf /var/www/html/tda-api'
                sh 'cp -r src /var/www/html/tda-api'
                sh 'sudo chmod 755 -R /var/www/html/tda-api'
                sh 'sudo chown www-data -R /var/www/html/tda-api'
            }
        }
    }
    post {
        always {
           cleanWs()
        }
    }
}
