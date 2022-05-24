echo 'give username:'
read u

mysqldump -d -u$u -p hrec_new > hrec_new_blank.sql 
git add *
git commit
git push https://github.com/nishishailesh/hrec_new main
