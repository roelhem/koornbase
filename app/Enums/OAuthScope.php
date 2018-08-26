<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-08-18
 * Time: 07:26
 */

namespace App\Enums;


use App\Enums\Traits\HasConfigFile;
use App\Enums\Traits\HasGraphQLEnumType;
use MabeEnum\Enum;
use Symfony\Component\Yaml\Yaml;

/**
 * Class OAuthScopes
 *
 * @package App\Enums
 *
 * @method static OAuthScope GRAPHQL_QUERIES()
 * @method static OAuthScope GRAPHQL_MUTATIONS()
 *
 * @property-read string $description
 */
final class OAuthScope extends Enum
{

    use HasConfigFile, HasGraphQLEnumType;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  ENUM-VALUE DEFINITIONS  ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    const ACCESS_PERSONAL_DATA = 'access-person-data';

    const GRAPHQL_QUERIES = 'graphql-queries';
    const GRAPHQL_MUTATIONS = 'graphql-mutations';

    const MANAGE_OAUTH_CLIENTS = 'manage-oauth-clients';

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  GraphQL OPTIONS  ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    protected static function getDescription()
    {
        return 'This `Enum`-type represent the Token Scopes in the system. These Scopes give an OAuth-Token more permissions.';
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // --------  USING THE CONFIG-FILE  ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the parsed content of the config file.
     *
     * @return array
     */
    protected static function parseFile()
    {
        $filename = str_replace('.php','.yaml',__FILE__);
        return Yaml::parseFile($filename);
    }

    /**
     * Returns the default config-file. This is used for the implementation of the magic methods.
     *
     * @return array
     */
    protected function defaultConfig()
    {
        return [
            'description' => $this->name,
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  PASSPORT HELPER METHODS  ----------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the array that is needed to register the scopes to Passport.
     *
     * @return array
     */
    public static function getScopeArray() {
        $res = [];
        foreach (self::getEnumerators() as $scope) {
            $res[$scope->getName()] = $scope->description;
        }
        return $res;
    }

}