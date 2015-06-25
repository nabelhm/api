<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\InfoSms\CreatePackageTestWorker;
use Muchacuba\InfoSms\CreateResellPackageTestWorker;
use Muchacuba\InfoSms\CreateTopicTestWorker;
use Muchacuba\RechargeCard\CreateCategoryTestWorker;
use Muchacuba\User\CreateAccountTestWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class InitFixturesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:fixtures:init')
            ->setDescription('Reset the data');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createInfoSmsTopics();
        $this->createInfoSmsPackages();
        $this->createInfoSmsResellPackages();
        $this->createRechargeCardCategories();
        $this->createRechargeCardPackages();
    }

    private function createInfoSmsTopics()
    {
        /** @var CreateTopicTestWorker $createTopicTestWorker */
        $createTopicTestWorker = $this->getContainer()
            ->get('muchacuba.info_sms.create_topic_test_worker');

        $createTopicTestWorker->create(
            '55704c96e0835',
            'Peloteros cubanos en ligas extranjeras',
            'Noticias sobre los peloteros cubanos que juegan en la MLB y otras ligas extranjeras.',
            8,
            1
        );

        $createTopicTestWorker->create(
            '55573fdc630b2',
            'Copa américa',
            'Noticias sobre la Copa América, con resultados, estadísticas, etc.',
            8,
            2
        );

        $createTopicTestWorker->create(
            '55573fae42e4d',
            'Liga española',
            'Información y resultados de las competiciones españolas de fútbol, incluye: La Liga, Copa del Rey y Supercopa de España. Noticias sobre los fichajes de los equipos más importantes como Barcelona, Real Madrid y Atlético de Madrid.',
            8,
            3
        );

        $createTopicTestWorker->create(
            '55573fc0e4b4b',
            'Liga alemana',
            'Información y resultados de la liga alemana. Noticias sobre los fichajes de los equipos más importantes como Bayern.',
            8,
            4
        );

        $createTopicTestWorker->create(
            '55573fc0e4c3a',
            'Liga inglesa',
            'Información y resultados de la liga inglesa. Noticias sobre los fichajes de los equipos más importantes como Manchester United, Manchester City y Chelsea.',
            8,
            5
        );

        $createTopicTestWorker->create(
            '55573fc0f5a5c',
            'Liga italiana',
            'Información y resultados de la liga italiana. Noticias sobre los fichajes de los equipos más importantes como Juventus, Milan y Roma.',
            8,
            6
        );

        $createTopicTestWorker->create(
            '5557400e229c5',
            'Eventos entre selecciones nacionales',
            'Información y resultados de partidos y torneos de conjuntos nacionales, partidos amistosos, etc.',
            1,
            7
        );
    }

    private function createInfoSmsPackages()
    {
        /** @var CreatePackageTestWorker $createPackageTestWorker */
        $createPackageTestWorker = $this->getContainer()
            ->get('muchacuba.info_sms.create_package_test_worker');

        $createPackageTestWorker->create(
            'p10',
            'Paquete 10',
            500,
            10
        );

        $createPackageTestWorker->create(
            'p20',
            'Paquete 20',
            1100,
            20
        );

        $createPackageTestWorker->create(
            'p50',
            'Paquete 50',
            2700,
            50
        );
    }

    private function createInfoSmsResellPackages()
    {
        /** @var CreateResellPackageTestWorker $createResellPackageTestWorker */
        $createResellPackageTestWorker = $this->getContainer()
            ->get('muchacuba.info_sms.create_resell_package_test_worker');

        $createResellPackageTestWorker->create(
            'rp0',
            10,
            0,
            "10 mensajes gratis para probar el sistema."
        );

        $createResellPackageTestWorker->create(
            'rp1',
            25,
            1,
            "25 sms (1 CUC)"
        );

        $createResellPackageTestWorker->create(
            'rp2',
            50,
            2,
            "50 sms (2 CUC)"
        );

        $createResellPackageTestWorker->create(
            'rp5',
            125,
            5,
            "125 sms (5 CUC)"
        );

        $createResellPackageTestWorker->create(
            'rp10',
            250,
            10,
            "250 sms (10 CUC)"
        );
    }

    private function createRechargeCardCategories()
    {
        /** @var CreateCategoryTestWorker $createCategoryTestWorker */
        $createCategoryTestWorker = $this->getContainer()
            ->get('muchacuba.recharge_card.create_category_test_worker');

        $createCategoryTestWorker->create(
            'c10',
            'Tarjeta de 10',
            10
        );

        $createCategoryTestWorker->create(
            'c20',
            'Tarjeta de 20',
            20
        );

        $createCategoryTestWorker->create(
            'c50',
            'Tarjeta de 50',
            50
        );
    }

    private function createRechargeCardPackages()
    {
        /** @var CreatePackageTestWorker $createPackageTestWorker */
        $createPackageTestWorker = $this->getContainer()
            ->get('muchacuba.recharge_card.create_package_test_worker');

        $createPackageTestWorker->create(
            'p10x10',
            'Paquete 10 de 10 cuc',
            'c10',
            10,
            80
        );

        $createPackageTestWorker->create(
            'p20x10',
            'Paquete 20 de 10 cuc',
            'c10',
            20,
            150
        );
    }
}
