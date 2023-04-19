#!/bin/bash
db=
if [[ -z $db ]]; then
    echo -e "\e[31mSet the database name before executing this script.\e[49m"
    exit
fi

user="${db}_user"
password=$(pass show ${user}) &>/dev/null

if [[ -z $password ]]; then
	pass generate --no-symbol ${user} 64 >/dev/null
	password=$(pass show ${user}) &>/dev/null
fi

while [[ -z $password ]]; do
    read -s -p "Password: " password
    if [[ -z $password ]]; then
        echo -e "\e[31mAn empty password is not allowed.\e[0m Enter a password or press Ctrl+C to abort."
    else
        echo
    fi
done

cd /tmp # move to a folder the postgres user can access
echo -e "\e[104mCreating user $user\e[49m"
sudo -u postgres createuser $user
sudo -u postgres psql -c "ALTER USER ${user} WITH PASSWORD '${password}';"
echo -e "\e[104mCreating database $db\e[49m"
sudo -u postgres createdb -O $user $db
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE ${db} TO ${user};"
cd $OLDPWD # move back to where we were

echo -e "\e[104mUpdating the .env file\e[49m"
[[ ! -f .env ]] && cp .env.example .env

app_key=$(hexdump -n 32 -e '4/4 "%08x"' /dev/random)
sed -i "s|^DB_PASSWORD=.*$|DB_PASSWORD=${password}|g" .env
sed -i "s|^DB_USERNAME=.*$|DB_USERNAME=${user}|g" .env
sed -i "s|^DB_DATABASE=.*$|DB_DATABASE=${db}|g" .env
sed -i "s|^APP_KEY=.*$|APP_KEY=${app_key}|g" .env
echo -e "\e[32mOK\e[37m"

echo -e "\e[104mUpdating the $db schema\e[49m"
php8.1 artisan migrate

echo -e "\e[104mUpdating the API documentation\e[49m"
php8.1 artisan l5-swagger:generate

echo Done.
