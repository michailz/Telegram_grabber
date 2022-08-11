# Telegram_grabber

### Install package
1. git clone https://github.com/michailz/Telegram_grabber.git
2. Install docker and docker-compose
3. Install Python3 (tested with 3.10.4), python3-pip, python3-venv
4. Create virtual environment for python: python3 -m venv telegram_env
5. Activate virtual environment: source telegram_env/bin/activate
6. Install requirements: pip install -r requirements.txt
7. Register for and obtain your Telegram API details from my.telegram.org
8. Rename config.ini.template to config.ini
9. Enter your api_id, api_hash and username from stage 7 to config.ini
10. Join to Telegram channels you want to grab 
11. Create directory postgres-data

### Run package
1. Run from the directory with docker-compose.yml: docker-compose up -d
2. if you are not in telegram_env run: source telegram_env/bin/activate
3. you could connect to adminer http://localhost:8080
    System: PostgreSQL
    Server: postgres
    Username: postgres
    Password: root
    Database: telegram
4. you can use simple search engine through http://localhost/
5. add some telegram channels via http://localhost  
If telegram link is https://t.me/MySuperChannel please add only MySuperChannel
Or you can directly add new channel to table orders via adminer
6. python3 go.py 
### Credits
statistics.lt@gmail.com