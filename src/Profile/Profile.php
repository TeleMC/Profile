<?PHP

namespace Profile;

use AbilityManager\AbilityManager;
use Core\Core;
use Core\util\Util;
use Equipments\Equipments;
use GuildManager\GuildManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use Status\Status;
use TeleMoney\TeleMoney;
use UiLibrary\UiLibrary;

class Profile extends PluginBase {

    private static $instance = null;

    public static function getInstance() {
        return self::$instance;
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->ui = UiLibrary::getInstance();
        $this->util = new Util(Core::getInstance());
        $this->money = TeleMoney::getInstance();
        $this->Equipments = Equipments::getInstance();
        $this->Stat = Status::getInstance();
        $this->ability = AbilityManager::getInstance();
        $this->Guild = GuildManager::getInstance();
    }

    public function ProfileUI(Player $player, string $target) {
        $per = "％";
        //$money = $this->money->getMoney($target);
        $money = "???";
        $Level = $this->util->getLevel($target);
        $Exp = $this->util->getExp($target);
        $MaxExp = $this->util->getMaxExp($target);
        $AllExp = $this->util->getAllExp($target);
        if (($target_p = $this->getServer()->getPlayer($target)) instanceof Player)
            $ExpBar = $this->util->ExpBar($target_p);
        else
            $ExpBar = "";
        $ExpPercentage = round(($Exp / $MaxExp) * 10000) / 100;
        $Job = $this->util->getJob($target);
        $ATK = $this->util->getATK($target);
        $DEF = $this->util->getDEF($target);
        $MATK = $this->util->getMATK($target);
        $MDEF = $this->util->getMDEF($target);
        if (($target_p = $this->getServer()->getPlayer($target)) instanceof Player) {
            $e_ATK = $this->Equipments->getATK($target_p);
            $e_DEF = $this->Equipments->getDEF($target_p);
            $e_MATK = $this->Equipments->getMATK($target_p);
            $e_MDEF = $this->Equipments->getMDEF($target_p);
        } else {
            $e_ATK = 0;
            $e_DEF = 0;
            $e_MATK = 0;
            $e_MDEF = 0;
        }
        $STR = $this->Stat->getStat($target, "힘");
        $SPD = $this->Stat->getStat($target, "민첩");
        $LUK = $this->Stat->getStat($target, "운");
        $HP = $this->Stat->getStat($target, "체력");
        $INT = $this->Stat->getStat($target, "지능");
        $Guild = $this->Guild->getGuild($target);
        $All_ATK = (float) $ATK + (float) $e_ATK;
        $All_MATK = (float) $MATK + (float) $e_MATK;
        $All_DEF = (float) $DEF + (float) $e_DEF;
        $All_MDEF = (float) $MDEF + (float) $e_MDEF;
        $inJob = $this->ability->getInbornJob($target);
        $d = $this->ability->getAbility($target);
        $AllBorns = $this->ability->getAllBorns($target);
        $borns = $this->ability->getBorns($target);
        $a = "";
        $b = "";
        if (count($AllBorns) > 0) {
            foreach ($AllBorns as $ability => $point) {
                $a .= "  - {$ability}: {$point}\n";
            }
        }
        if (count($borns) > 0) {
            foreach ($borns as $key => $ability) {
                $b .= "  - {$ability}\n";
            }
        }
        $form = $this->ui->CustomForm(function (Player $player, array $data) {
        });
        $form->setTitle("Tele Profile - {$target}");
        $form->addLabel("§c▶ §f기본 정보\n  §f- 돈 : {$money}테나\n  §f- 직업 : {$Job}\n  §f- 레벨 : Lv.{$Level}\n  §f- 경험치 : {$Exp} / {$MaxExp} ({$ExpPercentage}{$per})\n  §f- 누적경험치 : {$AllExp}\n  {$ExpBar}");
        //$form->addLabel("§c▶ §f스탯\n  §f- 힘 : {$STR}\n  §f- 지능 : {$INT}\n  §f- 민첩 : {$SPD}\n  §f- 체력 : {$HP}\n  §f- 운 : {$LUK}");
        //$form->addLabel("§c▶ §f피지컬 ( 기본 피지컬 + §a장비 피지컬 §f+ §b스탯 피지컬 §f)\n  §f- ATK : {$All_ATK}\n  §f- DEF : {$All_DEF}\n  §f- MATK : {$All_MATK}\n  §f- MDEF : {$All_MDEF}");
        $form->addLabel("§c▶ §f천직 및 천성\n  천직 : {$inJob}\n  천성 : {$d}");
        //$form->addLabel("§c▶ §f재능\n  §f숙련도:\n{$a}  §f재능:\n{$b}");
        $form->addLabel("§c▶ §f소속 길드 : {$Guild}");
        $form->sendToPlayer($player);
    }
}
