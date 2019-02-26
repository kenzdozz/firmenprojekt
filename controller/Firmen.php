<?php
namespace CTRL;

use DB\Model;

class Firmen
{
	static function createRecord () {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			header("Location: /");
			exit();
		}

		Firmen::validate($_REQUEST);

		$CBills = new Model('company_bills');
		$record = $CBills->create([
			'company_name' => $_REQUEST['company_name'],
			'company_id' => $_REQUEST['company_id'],
			'bill_amount' => $_REQUEST['bill_amount'],
			'bill_purpose' => $_REQUEST['bill_purpose'],
			'payment_date' => $_REQUEST['payment_date'],
		]);
		
		Firmen::jsonResponse(array(
			'status' => 201,
			'data' => $record
		));
	}

	static function updateRecord ($id) {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			header("Location: /");
			exit();
		}

		Firmen::validate($_REQUEST);

		$CBills = new Model('company_bills');
		$record = $CBills->update($id, [
			'company_name' => $_REQUEST['company_name'],
			'company_id' => $_REQUEST['company_id'],
			'bill_amount' => $_REQUEST['bill_amount'],
			'bill_purpose' => $_REQUEST['bill_purpose'],
			'payment_date' => $_REQUEST['payment_date'],
		]);

		Firmen::jsonResponse(array(
			'status' => 201,
			'data' => $record
		));
	}

	static function getAll() {
		$CBills = new Model('company_bills');
		return $CBills->findAll();
	}

	static function deleteRecord($id)
	{
		$CBills = new Model('company_bills');
		$deleted = $CBills->delete($id); 
		if ($deleted) return Firmen::jsonResponse(array(
			'status' => 200,
			'message' => 'Record deleted'
		));
		Firmen::jsonResponse(array(
			'status' => 500,
			'message' => 'Error deleting record.'
		));
	}

	private static function validate ($data) {
		$errors = new \stdClass;
		if (empty($data['company_name'])) {
			$errors->company_name = 'Company name is required';
		}
		if (empty($data['company_id'])) {
			$errors->company_id = 'Company ID is required';
		} else if (!is_numeric($data['bill_amount'])) {
			$errors->company_id = 'Company ID must be numeric';
		}
		if (empty($data['bill_amount'])) {
			$errors->bill_amount = 'Bill amount is required';
		} else if (!is_numeric($data['bill_amount'])) {
			$errors->bill_amount = 'Bill amount must be numeric';
		}
		if (empty($data['bill_purpose'])) {
			$errors->bill_purpose = 'Bill purpose is required';
		}
		if (empty($data['payment_date'])) {
			$errors->payment_date = 'Payment date is required';
		}

		if (count((array) $errors)) Firmen::jsonResponse(array(
			'status' => 400,
			'error' => 'Validation erros',
			'fields' => $errors
		));
	}

	private static function jsonResponse ($data) {
		header('Content-Type: application/json');
		echo json_encode($data);
		die();
	}
}