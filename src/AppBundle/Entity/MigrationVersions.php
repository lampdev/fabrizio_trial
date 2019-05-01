<?php

namespace AppBundle\Entity;

/**
 * MigrationVersions
 */
class MigrationVersions
{
    /**
     * @var datetime_immutable
     */
    private $executedAt;

    /**
     * @var string
     */
    private $version;


    /**
     * Set executedAt.
     *
     * @param datetime_immutable $executedAt
     *
     * @return MigrationVersions
     */
    public function setExecutedAt($executedAt)
    {
        $this->executedAt = $executedAt;

        return $this;
    }

    /**
     * Get executedAt.
     *
     * @return datetime_immutable
     */
    public function getExecutedAt()
    {
        return $this->executedAt;
    }

    /**
     * Get version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}
