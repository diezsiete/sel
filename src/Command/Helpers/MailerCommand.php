<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use ReflectionClass;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

trait MailerCommand
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var EmailValidator|null
     */
    private $emailValidator = null;

    protected $bccOptionDescription = "Agregar bcc";

    /**
     * @required
     */
    public function setMailer(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Configure
     */
    public function addOptionPeriodo()
    {
        $priorities = array_filter((new ReflectionClass(Email::class))->getConstants(), function($key) {
            return $key !== 'PRIORITY_MAP';
        }, ARRAY_FILTER_USE_KEY);
        $this
            ->addOption('from', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'From email [vacio toma el mail de la empresa]')
            ->addOption('subject', null, InputOption::VALUE_REQUIRED, "Subject del correo")
            ->addOption('to', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY)
            ->addOption('cc', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "Copia de email")
            ->addOption('cc-merge', null, InputOption::VALUE_NONE, "Los correos cc son unidos a los posibles cc que traiga el comando")
            ->addOption('bcc', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, $this->bccOptionDescription)
            ->addOption('bcc-merge', null, InputOption::VALUE_NONE, "Los correos bcc son unidos a los posibles bcc que traiga el comando")
            ->addOption('reply-to', null, InputOption::VALUE_REQUIRED, "Reply to email")
            ->addOption('priority', null, InputOption::VALUE_REQUIRED,
                "[" . print_r($priorities, 1) . "]")
            ->addOption('text', null, InputOption::VALUE_REQUIRED, "Enviar texto")
            ->addOption('image', null, InputOption::VALUE_REQUIRED, "Si el correo es una sola imagen, path de la imagen")
        ;
    }


    /**
     * @param $from
     * @param $to
     * @param array $options
     * @return Email
     */
    protected function buildEmail($from = null, $to = null, $options = ['subject' => '', 'cc' => '', 'bcc' => '', 'reply-to' => '', 'priority' => ''])
    {

        $email = new Email();
        $this
            ->setOption('from', $from, function ($value) use ($email) {
                $email->addFrom($value);
            })
            ->setOption('to', $to, function ($value) use ($email) {
                $email->addTo($value);
            })
            ->setOption('subject', $options, function ($value) use ($email) {
                $email->subject($value);
            })
            ->setOption('cc', $options, function ($value) use ($email) {
                $email->addCc($value);
            }, $this->input->getOption('cc-merge'))
            ->setOption('bcc', $options, function ($value) use ($email) {
                $email->addBcc($value);
            }, $this->input->getOption('bcc-merge'))
            ->setOption('reply-to', $options, function ($value) use ($email) {
                $email->replyTo($value);
            })
            ->setOption('priority', $options, function ($value) use ($email) {
                $email->priority($value);
            });


        if($text = $this->input->getOption('text')) {
            $email->text($text);
        }
        else if ($image = $this->input->getOption('image')) {
            $this->addImage($email, $image);
        }

        return $email;
    }

    protected function addImage(Email $email, $image)
    {
        $email
            ->embedFromPath($image, 'img')
            ->html('<img src="cid:img">');
    }

    protected function setOption($optionName, $overwriteOptions, $setCallback, $merge = false)
    {
        if(is_array($overwriteOptions)) {
            $option = null;
            if (isset($overwriteOptions[$optionName]) && $overwriteOptions[$optionName]) {
                $option = $overwriteOptions[$optionName];
            }
            if ($inputOption = $this->input->getOption($optionName)) {
                if($merge && $option){
                    $option = is_array($option) ? array_merge($option, $inputOption) : array_merge([$option], $inputOption);
                } else {
                    $option = $inputOption;
                }
            }
        }
        else {
            $option = $overwriteOptions;
            if ($inputOption = $this->input->getOption($optionName)) {
                $option = $inputOption;
            }
        }

        if($option) {
            if(is_array($option)) {
                foreach($option as $value) {
                    $setCallback($value);
                }
            } else {
                $setCallback($option);
            }
        }
        return $this;
    }

    protected function emailIsValid($email)
    {
        if(!$this->emailValidator) {
            $this->emailValidator = new EmailValidator();
        }
        return $this->emailValidator->isValid($email, new RFCValidation());
    }
}

