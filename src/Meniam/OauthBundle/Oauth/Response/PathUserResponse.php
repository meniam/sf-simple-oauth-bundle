<?php

namespace Meniam\OauthBundle\Oauth\Response;

class PathUserResponse extends AbstractUserResponse
{
    /**
     * @var array
     */
    protected $paths = array(
        'identifier' => null,
        'nickname' => null,
        'firstname' => null,
        'lastname' => null,
        'realname' => null,
        'email' => null,
        'profilepicture' => null,
    );

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getValueForPath('identifier');
    }

    /**
     * {@inheritdoc}
     */
    public function getNickname()
    {
        return $this->getValueForPath('nickname');
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->getValueForPath('firstname');
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->getValueForPath('lastname');
    }

    /**
     * {@inheritdoc}
     */
    public function getRealName()
    {
        return $this->getValueForPath('realname');
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->getValueForPath('email');
    }

    /**
     * {@inheritdoc}
     */
    public function getProfilePicture()
    {
        return $this->getValueForPath('profilepicture');
    }

    /**
     * Get the configured paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Configure the paths.
     *
     * @param array $paths
     */
    public function setPaths(array $paths)
    {
        $this->paths = array_merge($this->paths, $paths);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getPath($name)
    {
        return array_key_exists($name, $this->paths) ? $this->paths[$name] : null;
    }

    /**
     * Extracts a value from the response for a given path.
     *
     * @param string $path Name of the path to get the value for
     *
     * @return null|string
     */
    protected function getValueForPath($path)
    {
        $data = $this->data;
        if (!$data) {
            return null;
        }

        $steps = $this->getPath($path);
        if (!$steps) {
            return null;
        }

        if (is_array($steps)) {
            if (1 === count($steps)) {
                return $this->getValue(current($steps), $data);
            }

            $value = [];
            foreach ($steps as $step) {
                $value[] = $this->getValue($step, $data);
            }

            return trim(implode(' ', $value)) ?: null;
        }

        return $this->getValue((string) $steps, $data);
    }

    /**
     * @param string $steps
     * @param array  $data
     *
     * @return null|string
     */
    private function getValue($steps, array $data)
    {
        $value = $data;
        foreach (explode('.', $steps) as $step) {
            if (!array_key_exists($step, $value)) {
                return null;
            }

            $value = $value[$step];
        }

        return $value;
    }
}
