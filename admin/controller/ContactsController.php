<?php

    namespace Controller;

use CMS\Models\Controller\Controller;
use \CMS\Models\Contacts\Contact;

class ContactsController extends Controller
{
    use \CMS\Models\Actions\UserActions;

    public function index($params = null)
    {
        $contacts = Contact::allWhere(['trashed'=>0,'user_id' => $this->currentUser]);
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

    public function deleted($params = null)
    {
        $contacts = Contact::allWhere(['trashed'=>1,'user_id' => $this->currentUser]);
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

    public function create($params = null)
    {
        $contact = new Contact();
        if (isset($_POST['submit'])) {
            $contact = new Contact($_POST);
            $add = $contact->add();
        }
        $this->view(
            'Add contact',
            ['contacts/add-contact.php'],
            $params,
            [
                'output_form' => $add['output_form'],
                'contact' => $contact,
                'errors' => $add['errors'],
                'messages' => $add['messages']
            ]
        );
    }

    public function edit($params = null)
    {
        $contact = Contact::one($params[0]);
        if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
            $file_dest = 'files/users/' . $_SESSION['username'] . '/';
            $thumb_dest = 'files/thumbs/users/' . $_SESSION['username'] . '/';
            Contact::addProfileIMG($file_dest, $thumb_dest, $params);
        }

        if (isset($_POST['submit'])) {
            $contact->request = $_POST;
            $contact->user_id = $_SESSION['user_id'];
            $add = $contact->edit();
        }
        $this->view(
            'Add contact',
            ['contacts/edit-contact.php'],
            $params,
            [
                'contact' => $contact,
                'errors' => $add['errors'],
                 'messages' => $add['messages']
            ]
        );
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