<?php

namespace Profile;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\Player;

class EventListener implements Listener {

    public function __construct(Profile $plugin) {
        $this->plugin = $plugin;
    }

    public function onPacketReceived(DataPacketReceiveEvent $ev) {
        $pk = $ev->getPacket();
        if ($pk instanceof InventoryTransactionPacket && $pk->transactionType == InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY) {
            if ($ev->getPlayer()->isSneaking() && ($target = $ev->getPlayer()->getLevel()->getEntity($pk->trData->entityRuntimeId)) instanceof Player)
                $this->plugin->ProfileUI($ev->getPlayer(), $target->getName());
        }
    }

}
