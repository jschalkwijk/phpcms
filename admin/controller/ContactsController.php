<?php

use CMS\Models\Controller\Controller;
use \CMS\Models\Contacts\Contact;

class Contacts extends Controller
{
    use \CMS\Models\Actions\UserActions;

    public function index($params = null)
    {
        $contacts = Contact::allWhere(['trashed'=>0]);
        $this->UserActions($contacts[0]);
        $this->view(
            'Contacts',
            ['contacts/contacts.php'],
            $params,
            [
                'contacts' => $contacts,
                'trashed' => 0,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function deletedContacts($params = null)
    {
        $contacts = Contact::allWhere(['trashed'=>1]);
        $this->UserActions($contacts[0]);
        $this->view(
            'Contacts',
            ['contacts/contacts.php'],
            $params,
            [
                'contacts' => $contacts,
                'trashed' => 1,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function addContact($params = null)
    {
        if (!isset($_POST['submit'])) {
            $contact = new Contact();
            $this->view(
                'Add contact',
                ['contacts/add-contact.php'],
                $params,
                ['contact' => [$contact]]
            );
        } else {
            $contact = new Contact($_POST);
            $add = $contact->add();
            $this->view(
                'Add contact',
                ['contacts/add-contact.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'contact' => [$contact],
                    'errors' => $add['errors'],
                    'messages' => $add['messages']
                ]
            );
        }
    }

    public function editContact($params = null)
    {
        $contact = Contact::one($params[0]);
        if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
            $file_dest = 'files/users/' . $_SESSION['username'] . '/';
            $thumb_dest = 'files/thumbs/users/' . $_SESSION['username'] . '/';
            Contact::addProfileIMG($file_dest, $thumb_dest, $params);
        }

        if (!isset($_POST['submit'])) {
            $this->view(
                'Add contact',
                ['contacts/edit-contact.php'],
                $params,
                ['contact' => $contact]
            );
        } else {
            $contact = $contact[0];
            $contact->request = $_POST;
            $contact->user_id = $_SESSION['user_id'];
            $add = $contact->edit();
            $this->view(
                'Edit',
                ['contacts/edit-contact.php'],
                $params,
                ['output_form' => $add['output_form'],
                 'contact' => [$contact],
                 'errors' => $add['errors'],
                 'messages' => $add['messages']
                ]
            );
        }
    }

    public function info($params = null)
    {
        $this->UserActions('contacts');
        $contact = Contact::one($params[0]);
        $this->view(
            'Add contact',
            ['contacts/view-contact.php'],
            $params,
            ['contact' => $contact]
        );
    }
}

?>