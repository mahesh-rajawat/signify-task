<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Signify\ProductCustomAttributeGraphQL\Model\Product\UpdateProductAttribute;
use Exception;

class SetAdditionalTypeToExclusive extends Command
{
    private const COMMAND_NAME = "products:set:exclusive";

    /**
     * @param UpdateProductAttribute $updateProductAttribute
     */
    public function __construct(
        private readonly UpdateProductAttribute $updateProductAttribute
    ) {
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        try {
            $this->updateProductAttribute->execute();
            $output->writeln("All Products updated.");
        } catch (Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }

        return Command::SUCCESS;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription("Update product additional type attribute to ");
        parent::configure();
    }
}
