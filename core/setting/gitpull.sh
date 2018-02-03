if [ ! -d "/home/wwwsrc/rev1rd50c" ]; then
	cd /home/wwwsrc
	git clone https://github.com/Ltre/rev1rd50c.git
else
	cd /home/wwwsrc/rev1rd50c
	git pull
fi

cp /home/wwwsrc/rev1rd50c -r /home/wwwroot/rev1rd50c
mv /home/wwwroot/rev1rd50c /home/wwwroot/rev1rd50c.trash
mv /home/wwwroot/rev1rd50c /home/wwwroot/rev1rd50c
chmod -R 767 /home/wwwroot/rev1rd50c/core/data
chmod 777 /home/wwwroot/rev1rd50c/gitpull.sh
rm -f -r /home/wwwroot/rev1rd50c.trash
cd /home/wwwroot/rev1rd50c
