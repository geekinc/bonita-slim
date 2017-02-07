#!/bin/sh
echo UPDATE
for repo in colonial-medical-supplies; do
    (cd "${repo}" && git checkout master && git fetch --all && git reset --hard origin/master)
done

##################
echo DEPLOY
##################

sudo cp -rf ~/colonial-medical-supplies/* /var/www/html/
sudo rm /var/www/html/.env
sudo cp ~/colonial-medical-supplies/.env.server /var/www/html/.env
sudo chmod a+w /var/www/html/logs/app.log
sudo chown -R www-data:www-data /var/www/html