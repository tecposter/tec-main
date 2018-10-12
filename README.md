# TecPoster

## Route

* Landing
    * www/[?page={page}]
* User
    * i/login[?target={targetUrl}]
    * i/reg
* Article
    * www/article-create
    * www/article/{zcode}[?commitId={commitId}]
    * www/article-update/{zcode}
    * api/article-archive?articleId={articleId}
    * api/article-trash?articleId={articleId}
    * api/article-delete?articleId={articleId}
    * api/article-fetch-by-id?articleId={articleId}
    * www/article-commit-update/{commitId}
    * api/article-commit-publish?commitId={commitId}
    * api/article-commit-fetch-by-id?commitId={commitId}

## SSL

```
sudo certbot --nginx certonly -w /<project/path>/site/public -d www.tecposter.cn
sudo certbot --nginx certonly -w /<project/path>/site/public -d api.tecposter.cn
sudo certbot --nginx certonly -w /<project/path>/site/public -d i.tecposter.cn
sudo certbot --nginx certonly -w /<project/path>/site/static -d static.tecposter.cn
```

## Get started

```
composer create-project gap/project tec-main
```
