# TecPoster Main

## SSL

```
sudo certbot --nginx certonly -w /var/space/tec/tec-main/site/public -d www.tecposter.cn
sudo certbot --nginx certonly -w /var/space/tec/tec-main/site/public -d api.tecposter.cn
sudo certbot --nginx certonly -w /var/space/tec/tec-main/site/static -d static.tecposter.cn
```

## Get started

```
composer create-project gap/project tec-main
```
