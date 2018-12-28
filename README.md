# TecPoster

## Route

* Landing
    * www/[?page={page}]
* User
    * i
        * login[?target={targetUrl}]
        * reg
        * article-list-draft
* Article
    * www
        * article/{slug}[?commitId={commitId}]
        * article-req-creating
        * article-req-updating/{slug}
        * article-update-commit/{code}
    * api
        * article-archive?articleId={articleId}
        * article-trash?articleId={articleId}
        * article-delete?articleId={articleId}
        * article-fetch-by-id?articleId={articleId}
        * article-commit-publish?commitId={commitId}
        * article-commit-fetch-by-id?commitId={commitId}

## SSL

```
sudo certbot --nginx certonly -w /<project/path>/site/public -d www.tecposter.cn
sudo certbot --nginx certonly -w /<project/path>/site/public -d api.tecposter.cn
sudo certbot --nginx certonly -w /<project/path>/site/public -d i.tecposter.cn
sudo certbot --nginx certonly -w /<project/path>/site/static -d static.tecposter.cn
```

## RSA

```
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -outform PEM -pubout -out public.pem
```


## Get started

```
composer create-project gap/project tec-main
```
