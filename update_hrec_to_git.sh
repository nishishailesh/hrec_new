echo 'give username:'
read $u
echo 'give pass:'
read $p

mysqldump -d -u$u hrec_new -p$p > hrec_new_blank.sql 
git add *
git commit
git push https://github.com/nishishailesh/hrec_new main
