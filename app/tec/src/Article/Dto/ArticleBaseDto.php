<?php
namespace Tec\Article\Dto;

use Gap\Dto\DateTime;

/**
 * articleId  int(10) unsigned                  NO      PRI    <null>     auto_increment
 * commitId   int(10) unsigned                  NO             0
 * userId     int(10) unsigned                  NO             <null>
 * slug       varbinary(56)                     NO      UNI    <null>
 * status     enum('normal','archive','trash')  NO             normal
 * access     enum('public','private')          NO             private
 * created    datetime                          NO             <null>
 * changed    datetime                          NO             <null>
 **/

class ArticleBaseDto extends DtoBase
{
    const STATUS_DEFAULT = 'normal';
    const ACCESS_DEFAULT = 'private';

    const STATUS_NORMAL = 'normal';
    const STATUS_ARCHIVE = 'archive';
    const STATUS_TRASH = 'trash';

    const ACCESS_PUBLIC = 'public';
    const ACCESS_PRIVATE = 'private';
}
