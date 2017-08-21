<?php

    namespace Controller;

use CMS\Models\Controller\Controller;
use \CMS\Models\Contacts\Contact;
use CMS\Models\Actions\Actions;

class ContactsController extends Controller
{
    use \CMS\Models\Actions\UserActions;

    public function index($response,$params = null)
    {
        $contacts = Contact::allWhere(['trashed'=>0,'user_id' => $this->currentUser]);
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

    public function deleted($response,$params = null)
    {
        $contacts = Contact::allWhere(['trashed'=>1,'user_id' => $this->currentUser]);
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

    public function create($response,$params = null)
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

    public function edit($response,$params = null)
    {
        $contact = Contact::one($params['id']);
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

    public function info($response,$params = null)
    {
        $contact = Contact::one($params['id']);
        $this->view(
            'Add contact',
            ['contacts/view-contact.php'],
            $params,
            ['contact' => $contact]
        );
    }
    public function action($response, $params)
    {
        $this->UserActions(new Contact());

    }

    public function approve($response,$params)
    {
        $contact = Contact::one($params['id']);
        Actions::approve_selected($contact,$params['id']);
        header("Location: ".ADMIN.$contact->table);
    }

    public function hide($response,$params)
    {
        $contact = Contact::one($params['id']);
        Actions::hide_selected($contact,$params['id']);
//        header("Location: ".ADMIN.$contact->table);
    }

    public function trash($response,$params)
    {
        $contact = Contact::one($params['id']);
        Actions::trash_selected($contact,$params['id']);
        header("Location: ".ADMIN.$contact->table.'/deleted');
    }

    public function destroy($response,$params)
    {
        $contact = Contact::one($params['id']);
        $contact->delete();
        header("Location: ".ADMIN.$contact->table.'/deleted');
    }

}

?>