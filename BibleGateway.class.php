<?php

/**
* 
*/
class BibleGateway
{
	const URL = 'http://www.biblegateway.com';

	protected $version;
	protected $reference;
	protected $text = '';
	protected $copyright = '';
	protected $permalink;

	public function __construct($version = 'ESV')
	{
		$this->version = $version;
	}

	public function __get($name)
	{
		if ($name === 'permalink')
		{
			return $this->permalink = self::URL.'/passage?'.http_build_query(['search' => $this->reference,'version' => $this->version]]);
		}
		return $this->$name;
	}

	public function __set($name, $value)
	{
		if(in_array($name, ['version', 'reference']))
		{
			$this->$name = $value;
			$this->searchPassage($this->reference);
		}
	}

	public function searchPassage($passage)
	{
		$this->reference = $passage;
		$this->text = '';
		$url = self::URL.'/passage?'.http_build_query(['search' => $passage,'version' => $this->version]);
		$html = file_get_contents($url);
		$dom = new DOMDocument;
		libxml_use_internal_errors(true);
		$dom->loadHTML($html);
		libxml_use_internal_errors(false);
		$xpath = new DOMXPath($dom);
		$context = $xpath->query("//div[@class='passage-wrap']")->item(0);
		$pararaphs =  $xpath->query("//div[@class='passage-wrap']//p");
		$verses = $xpath->query("//div[@class='passage-wrap']//span[contains(@class, 'text')]");
		foreach ($pararaphs as $paragraph)
		{
			if($xpath->query('.//span[contains(@class, "text")]', $paragraph)->length)
			{
				$results = $xpath->query("//sup[contains(@class, 'crossreference') or contains(@class, 'footnote')] | //div[contains(@class, 'crossrefs') or contains(@class, 'footnotes')]", $paragraph);
				foreach($results as $result)
				{
					$result->parentNode->removeChild($result);
				}

				// $chapter = $xpath->query("span[@class='chapternum']", $verse);
				// if($chapter->length)
				// {
				// 	var_dump($chapter->item(0));
				// }
				$this->text .= $dom->saveHTML($paragraph);
			}
			else
			{
				$this->copyright = $dom->saveHTML($paragraph);
			}
		}
		return $this;
	}

	public function getVerseOfTheDay()
	{
		$url = self::URL.'/votd/get/?'.http_build_query(['format' => 'json', 'version' => $this->version]);
		$votd = json_decode(file_get_contents($url))->votd;
		$this->text = $votd->text;
		$this->reference = $votd->reference;
		return $this;
	}
}
