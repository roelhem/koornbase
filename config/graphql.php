<?php

return [

    'query' => \App\Http\GraphQL\Query::class,
    'mutation' => \App\Http\GraphQL\Mutation::class,

    'use' => [
        'enums' => [
            'MembershipStatusType' => \App\Enums\MembershipStatus::class,
            \App\Enums\PhoneNumberFormat::class,
            \App\Enums\PhoneNumberType::class,
            \App\Enums\PersonNameFormat::class,
            \App\Enums\OAuthClientType::class,
            \App\Enums\OAuthScope::class,
            \App\Enums\AvatarType::class,
        ],
        'interfaces' => [
            'PersonContactEntry' => \App\Http\GraphQL\Interfaces\PersonContactEntryInterface::class,
            'Category'           => \App\Http\GraphQL\Interfaces\CategoryInterface::class,
            'Avatar'             => \App\Http\GraphQL\Interfaces\AvatarInterface::class,
            'OAuthClient'        => \App\Http\GraphQL\Interfaces\OAuthClientInterface::class,
        ],
        'scalars' => [
            'CountryCode' => \App\Http\GraphQL\Scalars\CountryCodeType::class,
            'Locale'      => \App\Http\GraphQL\Scalars\LocaleType::class,

            'Email'       => \App\Http\GraphQL\Scalars\EmailType::class,
            'URL'         => \App\Http\GraphQL\Scalars\URLType::class,

            'Date'        => \App\Http\GraphQL\Scalars\DateType::class,
            'DateTime'    => \App\Http\GraphQL\Scalars\DateTimeType::class,
        ],
        'modelTypes' => [
            \App\Http\GraphQL\Types\Models\UserType::class,
            \App\Http\GraphQL\Types\Models\PersonType::class,
            \App\Http\GraphQL\Types\Models\PersonAddressType::class,
            \App\Http\GraphQL\Types\Models\PersonPhoneNumberType::class,
            \App\Http\GraphQL\Types\Models\PersonEmailAddressType::class,
            \App\Http\GraphQL\Types\Models\MembershipType::class,
            \App\Http\GraphQL\Types\Models\KoornbeursCardType::class,
            \App\Http\GraphQL\Types\Models\CertificateType::class,
            \App\Http\GraphQL\Types\Models\CertificateCategoryType::class,
            \App\Http\GraphQL\Types\Models\GroupType::class,
            \App\Http\GraphQL\Types\Models\GroupCategoryType::class,
            \App\Http\GraphQL\Types\Models\GroupEmailAddressType::class,
            // OAuth
            \App\Http\GraphQL\Types\Models\OAuth\OAuthPersonalClientType::class,
            \App\Http\GraphQL\Types\Models\OAuth\OAuthPasswordClientType::class,
            \App\Http\GraphQL\Types\Models\OAuth\OAuthCredentialsClientType::class,
            \App\Http\GraphQL\Types\Models\OAuth\OAuthAuthCodeClientType::class,
            \App\Http\GraphQL\Types\Models\OAuth\OAuthTokenType::class,
        ],
        'otherTypes' => [
            // Avatars
            'UserAvatar'   => \App\Http\GraphQL\Types\Avatars\UserAvatarType::class,
            'PersonAvatar' => \App\Http\GraphQL\Types\Avatars\PersonAvatarType::class,

            // Contact information
            'PostalAddress' => \App\Http\GraphQL\Types\PostalAddressType::class,
            'PhoneNumber'   => \App\Http\GraphQL\Types\PhoneNumberType::class,
            'Country'       => \App\Http\GraphQL\Types\CountryType::class,
            'EmailAddress'  => \App\Http\GraphQL\Types\EmailAddressType::class,

            // Person helpers
            'MembershipStatus' => \App\Http\GraphQL\Types\MembershipStatusType::class,
            'PersonName'       => \App\Http\GraphQL\Types\PersonNameType::class,

            // OAuth
            'OAuthTokenIssue' => \App\Http\GraphQL\Types\OAuthTokenIssueType::class,

            // Pivots
            'PersonGroupPivot' => \App\Http\GraphQL\Types\Pivots\PersonGroupPivotType::class,
        ],
    ],


    'output_formats' => [
        'date' => 'Y-m-d',
        'datetime' => 'Y-m-d H:i:s',
    ],
];
