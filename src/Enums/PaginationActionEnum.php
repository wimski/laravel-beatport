<?php

namespace Wimski\Beatport\Enums;

/**
 * @method static PaginationActionEnum PAGE()
 * @method static PaginationActionEnum FIRST()
 * @method static PaginationActionEnum LAST()
 * @method static PaginationActionEnum NEXT()
 * @method static PaginationActionEnum PREV()
 * @method static PaginationActionEnum ADD()
 * @method static PaginationActionEnum SUB()
 */
class PaginationActionEnum extends AbstractEnum
{
    public const PAGE  = 'page';
    public const FIRST = 'first';
    public const LAST  = 'last';
    public const NEXT  = 'next';
    public const PREV  = 'prev';
    public const ADD   = 'add';
    public const SUB   = 'sub';
}
