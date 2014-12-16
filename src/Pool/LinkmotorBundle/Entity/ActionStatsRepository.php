<?php

namespace Pool\LinkmotorBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ActionStatsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActionStatsRepository extends EntityRepository
{
    /**
     * @param $field
     * @param User $user
     * @param Project $project
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    public function getSum($field, User $user, Project $project, \DateTime $from = null, \DateTime $to = null)
    {
        $parameters = array('project' => $project, 'user' => $user);

        $dql = "SELECT SUM(s.{$field})
                FROM PoolLinkmotorBundle:ActionStats s
                WHERE s.project = :project AND s.user = :user";

        if ($from) {
            $dql .= " AND s.date >= :from";
            $parameters['from'] = $from;
        }

        if ($to) {
            $dql .= " AND s.date <= :to";
            $parameters['to'] = $to;
        }

        $value = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($parameters)
            ->getSingleScalarResult();

        if (!$value) {
            $value = 0;
        }

        return $value;
    }

    /**
     * @param string $field
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function getThisMonth($field, User $user, Project $project)
    {
        $from = new \DateTime('first day of this month');
        $to = new \DateTime('last day of this month');

        return $this->getSum($field, $user, $project, $from, $to);
    }

    /**
     * @param string $field
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function getLastMonth($field, User $user, Project $project)
    {
        $from = new \DateTime('first day of last month');
        $to = new \DateTime('last day of last month');

        return $this->getSum($field, $user, $project, $from, $to);
    }
}