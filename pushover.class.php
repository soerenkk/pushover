<?php

/**
 * Class Pushover
 * @author Søren K. Kjeldsen <soerenkk@soerenkk.dk>
 * @copyright 2014
 * @link https://github.com/soerenkk/pushover Source of this source code.
 * @link https://pushover.net/api Pushover API documentation.
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 * @version 1.0.0 Pushover version
 * @version 1.0.0 Class version
 */
class Pushover
{
    
    // Following variables are system variables \\
    
    /**
     * @var string
     * @api
     */
    private $message_url = "https://api.pushover.net/1/messages.json";

    /**
     * @var string
     */
    private $sounds_url = "https://api.pushover.net/1/sounds.json";
    
    // Following variables are required \\
    
    /**
     * @var null|string Initially NULL to check if valid, once set it should be a string of 30 characters (current token format).
     */
    private $token = NULL;

    /**
     * @var string Regular expression
     */
    // Removed the length from the regex, the API documentation claims that the length of the token is 30 characters long,
    // however I have received a 33 character length token for one of my apps.
    //private $token_regex = "([A-Za-z0-9{33}])+";
    private $token_regex = "/([A-Za-z0-9])+/";
    
    /**
     * @var null|string Initially NULL to check if valid, once set it should be a string of 30 characters (current token format).
     */
    private $user = NULL;
    
    // Will be implemented in future versions \\
    //private $user_regex = "";
    
    /**
     * @var null|string Initially NULL to check if valid, once set it should be a string of 30 characters (current token format).
     */
    private $message = NULL;
    
    
    // Following variables are optional variables \\

    /**
     * @var null
     */
    private $device = NULL;

    /**
     * @var null
     */
    private $title = NULL;

    /**
     * @var null
     */
    private $url = NULL;

    /**
     * @var null
     */
    private $url_title = NULL;

    /**
     * @var null
     */
    private $priority = NULL;

    /**
     * @var null|DateTime Initially NULL to check if set, once set it should be a DateTime object.
     */
    private $timestamp = NULL;

    /**
     * @var null|(array|object) Initially NULL to check if set, once set it should be an array|object, haven't decided yet.
     */
    private $sound = NULL;
	
	/**
	 * @param $token
	 */
	public function __construct($token)
	{
		
		$this->setToken($token);
		
		$this->setSounds();
		
    }
	
	/**
	 * @param $token The application token.
	 * @throws Exception If the supplied token doesn't match the regular expression.
	 */
	private function setToken($token)
	{
		if (preg_match($this->token_regex, $token)) {
			$this->token = $token;
		} else {
			throw new Exception("Invalid token: did not pass the regular expression test");
		}
	}
	
	private function setSounds()
	{
		
		$fields = array(
			'token' => $this->token,
		);

		$url = $this->sounds_url . "?" . http_build_query($fields);
		
		//open connection \\
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data \\
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		//execute post
		$result = curl_exec($ch);
		
		//close connection
		curl_close($ch);
		
		$result = @json_decode($result);
		
		if ((!$result) OR ($result->status != 1))
		{
			throw new Exception("Invalid request: Something in the request where invalid, could not get the sound list");
		}
		
		$this->sound = (array)$result->sounds;
		
	}
	
	public function setUser($user)
	{
		if (preg_match($this->user_regex, $user)) {
			$this->user = $user;
		} else {
			throw new Exception("Invalid token: did not pass the regular expression test");
		}
	}
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function push()
	{
		
	}
	
}


?>
