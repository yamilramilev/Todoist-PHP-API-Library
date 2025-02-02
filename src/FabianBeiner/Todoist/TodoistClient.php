<?php
/**
 * Todoist-PHP-API-Library
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @version 2.0.0 <2022-11-25>
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class TodoistClient.
 */
class TodoistClient extends GuzzleClient
{
    /*
     * Use Traits.
     */
    use TodoistCommentsTrait;
    use TodoistHelpers;
    use TodoistLabelsTrait;
    use TodoistProjectsTrait;
    use TodoistSectionsTrait;
    use TodoistTasksTrait;

    /**
     * @var string URL of the Todoist REST API.
     */
    protected string $restApiUrl = 'https://api.todoist.com/rest/v2/';

    /**
     * @var string 2-letter code that specifies the language for due_string parameters.
     */
    protected string $defaultInputLanguage = 'en';

    /**
     * @var array All valid languages.
     */
    protected array $validLanguages = [
        'cs',
        'da',
        'de',
        'en',
        'es',
        'fi',
        'fr',
        'it',
        'ja',
        'ko',
        'nb',
        'nl',
        'pl',
        'pt',
        'pt_BR',
        'ru',
        'sv',
        'tr',
        'zh',
        'zh_CN',
        'zh_TW',
    ];

    /**
     * TodoistClient constructor.
     *
     * @param string $apiToken     API token to access the Todoist API.
     * @param string $languageCode 2-letter code that specifies the language for due_string parameters.
     * @param array  $guzzleConf   Configuration to be passed to Guzzle client.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function __construct(string $apiToken, string $languageCode = 'en', array $guzzleConf = [])
    {
        $apiToken = trim($apiToken);
        if (40 !== strlen($apiToken)) {
            throw new TodoistException('The provided API token is invalid.');
        }

        $languageCode = strtolower(trim($languageCode));
        if (in_array($languageCode, $this->validLanguages)) {
            $this->defaultInputLanguage = $languageCode;
        }

        $defaults = [
            'headers'         => [
                'Accept-Encoding' => 'gzip',
            ],
            'http_errors'     => false,
            'timeout'         => 15, // Time to execute something at the API.
            'connect_timeout' => 15, // Time to connect to the API.
        ];

        $config                             = array_replace_recursive($defaults, $guzzleConf);
        $config['base_uri']                 = $this->restApiUrl;
        $config['headers']['Authorization'] = sprintf('Bearer %s', $apiToken);

        parent::__construct($config);
    }
}
