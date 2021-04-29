<?php /** @noinspection NonSecureArrayRandUsageInspection */
/*
 * Copyright 2021 LABOR.digital
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2021.02.21 at 20:44
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Command;


use LaborDigital\T3BA\Core\Di\ContainerAwareTrait;
use LaborDigital\T3BA\ExtConfigHandler\Command\ConfigureCliCommandInterface;
use LaborDigital\T3BA\Tool\OddsAndEnds\NamingUtil;
use LaborDigital\Typo3BetterApiExample\Configuration\Table\Article\ArticleTable;
use LaborDigital\Typo3BetterApiExample\Configuration\Table\Article\AuthorTable;
use Neunerlei\Arrays\Arrays;
use Neunerlei\TinyTimy\DateTimy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PopulatorCommand
 *
 * Configuring a command is easy as pie! Create a class in /Classes/Command,
 * let it extend the "Command" class, provided by the symfony console component and
 * also implement the ConfigureCliCommandInterface. The command configuration is done using
 * the "configure()" method provided by the command. All information are provided to the TYPO3 implementation.
 *
 * This command shows you how to create a cli command that populates the database tables using the
 * TYPO3 data handler using T3BA.
 *
 * @package LaborDigital\Typo3BetterApiExample\Command
 */
class PopulatorCommand extends Command implements ConfigureCliCommandInterface
{
    use ContainerAwareTrait;
    
    protected const FIRST_NAMES
        = [
            'Fay',
            'Fabian',
            'Frances',
            'Franklin',
            'Florence',
            'Gabielle',
            'Gustav',
            'Grace',
            'Gaston',
            'Gert',
            'Gordon',
            'Humberto',
            'Hanna',
            'Henri',
            'Hermine',
            'Harvey',
            'Helene',
            'Iris',
            'Isidore',
            'Isabel',
        ];
    
    protected const LAST_NAMES
        = [
            'Bell',
            'Bender',
            'Ellis',
            'Ellison',
            'Emerson',
            'Mccray',
            'Mccullough',
            'Mcdaniel',
            'Todd',
            'Torres',
            'Townsend',
            'Tran',
            'Travis',
            'Trevino',
            'Wong',
            'Wood',
            'Woodard',
            'Hanson',
            'Hardin',
            'Harding',
            'Hardy',
        ];
    
    protected const WORDS
        = [
            'tree',
            'water',
            'river',
            'sky',
            'house',
            'green',
            'purple',
            'yellow',
            'human',
            'warm',
            'winter',
            'wonderland',
        ];
    
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription('A helper command that will generate dummy records');
    }
    
    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // As a word of caution, if you don't know what the data handler is, you should
        // probably pause for a moment and take a look at the docs before you continue
        // https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Typo3CoreEngine/Database/Index.html
        
        $this->populateAuthors();
        $this->populateArticles();
        
        return 0;
    }
    
    protected function populateAuthors(): void
    {
        // Before we start, in this command we use the ContainerAwareTrait
        // It allows us to directly require a public service from TYPO3 symfony container using $this->getService().
        // We could also create a new instance using the TYPO3 GeneralUtility using $this->makeInstance().
        // In addition to that we also have access to a list of commonly used services, those can be accessed
        // using $this->commonServices(), or $this->cs() for short.
        $cs = $this->cs();
        
        // Pro-tip: The ContainerAwareTrait is ALWAYS aware of the container, even if it was never injected.
        // It will use the container singleton as a fallback. This way you can use it in Scheduler Tasks,
        // UserFunc definitions, or in cli commands.
        
        // Double-tip: There is a static variant doing the same called StaticContainerAwareTrait
        
        // T3BA provides you with a nicer way to create records in the TYPO3 core data handler.
        // You can either use the Data handler directly, or, like we do it here, create a record
        // specific data handler that has a much easier API to work with.
        $handler = $cs->dataHandler->getRecordDataHandler(AuthorTable::class);
        
        // To access the pid storage we use the typo context object. TypoContext
        // is basically your default "Context" object that was introduced in TYPO v9 but on steroids,
        // it provides you with an autocomplete-friendly interface and a bunch of new aspects to work with.
        // One of those aspects is the "PID" aspect, that has access to all your registered pids
        $storagePid = $cs->typoContext->pid()->get('storage.author');
        
        // DateTimy is an extension of a PHP native DateTime object, that comes with
        // some build in formatting options.
        $now = new DateTimy();
        for ($i = 0; $i < 5; $i++) {
            // Calculate a birthday for our author
            $then = (clone $now)->modify('-' . round(random_int(20, 60)) . ' years');
            $then = $then->modify('-' . round(random_int(1, 31)) . ' days');
            $then = $then->modify('+' . round(random_int(1, 12)) . ' months');
            
            // Create the author record
            $handler->save([
                'first_name' => static::FIRST_NAMES[array_rand(static::FIRST_NAMES)],
                'last_name' => static::LAST_NAMES[array_rand(static::LAST_NAMES)],
                'birthday' => $then->formatSqlDate(),
                'pid' => $storagePid,
            ]);
        }
    }
    
    protected function populateArticles(): void
    {
        $cs = $this->cs();
        
        // In this example we want to create relations for our newly created records
        // therefore we use the data handler service and its processData() method.
        // It works exactly as you know it from the data handler but has a nicer interface to work with
        $handler = $cs->dataHandler;
        
        // Again, like in the author population we receive the pid where we want to store our articles
        $storagePid = $cs->typoContext->pid()->get('storage.article');
        
        // Next, we will use the db service to retrieve the uid of all (not hidden and not deleted) authors
        $authorIds = $cs->db->getQuery(AuthorTable::class)->getAll(['uid']);
        // The result is an array like [["uid" => 1], ["uid" => 4], ["uid" => 5]], but we want
        // a plain list of uids Arrays::getList helps us with that by extracting the uid value of the child arrays.
        $authorIds = Arrays::getList($authorIds, 'uid');
        
        for ($i = 0; $i < 5; $i++) {
            // Generate some random content elements
            $contents = [];
            for ($j = 0; $j < max(1, round(random_int(1, 4))); $j++) {
                // To create a new record, we have to provide it with an "id", even if that id
                // is foo-code and will never actually be used.
                // Attention: No touchy-touchy on the "_" key! The underscore is a protected key to
                // separate a table name and a uid in a reference creation. Don't use it in a new id!
                $id = 'NEW' . $j;
                $contents[$id] = [
                    'CType' => 'text',
                    'pid' => $storagePid,
                    'bodytext' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>',
                ];
            }
            
            $headline = static::WORDS[array_rand(static::WORDS)] . ' '
                        . static::WORDS[array_rand(static::WORDS)]
                        . ' article';
            
            // ProcessData is you go-to solution to create or modify records with the data handler.
            // It will internally create a new data handler for you and execute the required overhead.
            $handler->processData([
                // You could either hardcode the table name, or be fancy and use a reference on it.
                // You can use either a repository, a model or a table configuration class
                // NamingUtil::resolveTableName will then convert that reference into the real table name.
                NamingUtil::resolveTableName(ArticleTable::class) => [
                    'NEWarticle1' => [
                        'pid' => $storagePid,
                        'headline' => $headline,
                        'published' => (new DateTimy())->formatSql(),
                        // We concatenate the IDs of the created contents as a field value to tell
                        // the data handler that it should create the relations for us
                        'content' => implode(',', array_keys($contents)),
                        // We use the name of the author table with an underscore and the actual
                        // uid to tell the data handler that we want to reference an external table
                        'author' => NamingUtil::resolveTableName(AuthorTable::class) . '_'
                                    . $authorIds[array_rand($authorIds)],
                    ],
                ],
                
                // In addition to our articles we tell the data handler that there are multiple
                // content elements it should create for us as well.
                'tt_content' => $contents,
            ]);
        }
    }
    
    
}
