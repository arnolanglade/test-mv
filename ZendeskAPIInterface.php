<?php
declare(strict_types=1);

namespace MobilityWork\Service;

/**
 * Je me ne suis pas fan des suffix mais ici je n'ai pas le choix.
 */
interface ZendeskAPIInterface
{
    /**
     * Il faudrait travailler ici avec des object plutôt qu'un tableau. On introduirait un
     * nouveau "type" avec son comportement associé.
     */
    public function createUser(array $user): string;
    public function createTicket(array $ticket): array;
}