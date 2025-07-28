<?php

arch()->preset()->laravel();

arch()->preset()->security();

arch('Application uses strict typing')
    ->expect('App')
    ->toUseStrictTypes();
