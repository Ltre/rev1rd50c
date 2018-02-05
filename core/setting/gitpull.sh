if [ ! -d "/home/wwwsrc" ]; then
    mkdir /home/wwwsrc;
fi;

if [ ! -d "/home/wwwsrc/rev1rd50c" ]; then
    cd /home/wwwsrc;
    git clone https://github.com/Ltre/rev1rd50c.git;
else
    cd /home/wwwsrc/rev1rd50c;
    git pull;
fi

if [ ! -d "/home/wwwroot/rev1rd50c" ]; then
    mkdir /home/wwwroot/rev1rd50c;
fi

mv /home/wwwroot/rev1rd50c /home/wwwroot/rev1rd50c.trash;
cp /home/wwwsrc/rev1rd50c -r /home/wwwroot/rev1rd50c;
chmod -R 767 /home/wwwroot/rev1rd50c/core/data;
chmod +x /home/wwwroot/rev1rd50c/core/setting/gitpull.sh;
rm -f -r /home/wwwroot/rev1rd50c.trash;

cd /home/wwwroot/rev1rd50c
