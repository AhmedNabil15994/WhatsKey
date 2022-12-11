<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/changeLogs/add/uploadImage',
        '/changeLogs/edit/*/editImage',

        '/sliders/add/uploadImage',
        '/sliders/edit/*/editImage',

        '/sections/add/uploadImage',
        '/sections/edit/*/editImage',

        '/users/edit/*/editImage',
        '/livewire/message/*',

        '/users/add/uploadImage',
        '/profile/personalInfo/editImage',

        '/tickets/add/uploadImage',
        '/tickets/edit/*/editImage',
        '/tickets/view/*/uploadCommentFile',

        '/services/webhooks/*',

        '/bots/add/uploadImage/*',
        '/bots/edit/editImage/*',

        '/botPlus/add/uploadImage',
        '/botPlus/edit/*/uploadImage',

        '/groupMsgs/add/uploadImage/*',
    ];
}
