<?php namespace EFrane\Tinkr\Environment;

class ComposerResult
{
  protected $ambiguous = false;
  protected $query     = '';
  protected $results  = [];

  /**
   * ComposerResult constructor.
   *
   * @param string $query
   * @param array $results
   */
  public function __construct($query, array $results)
  {
    $this->query = $query;
    $this->results = $results;
  }

  /**
   * @return boolean
   */
  public function isAmbiguous()
  {
    return $this->ambiguous;
  }

  /**
   * @param boolean $ambiguous
   */
  public function setAmbiguous($ambiguous)
  {
    $this->ambiguous = $ambiguous;
  }

  /**
   * @return string
   */
  public function getQuery()
  {
    return $this->query;
  }

  /**
   * @param string $query
   */
  public function setQuery($query)
  {
    $this->query = $query;
  }

  /**
   * @return array
   */
  public function getResults()
  {
    return $this->results;
  }

  /**
   * @param array $results
   */
  public function setResults($results)
  {
    $this->results = $results;
  }

  public function getVersion()
  {
    if ($this->isAmbiguous())
    {
      throw new \BadMethodCallException("Result is ambiguous. Must resolve first.");
    }

    return $this->results[0];
  }

  /**
   * @param int $solution the selected version
   */
  public function resolve($solution)
  {
    $this->ambiguous = false;
    $this->results = [$this->results[$solution]];
  }
}