
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Firmen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="./css/main.css">
    <link rel="stylesheet" type="text/css" media="screen" href="./css/font-awesome.min.css">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>

<body>
    <header>
        <div id="loadBar"></div>
        <div class="container header">
            <div class="logo-div">
                <h2 style="margin: 0.4rem 0;">Firmen</h2>
            </div>
        </div>
    </header>

    <div class="wrapper">
        <span class="nav-toggle hide"><i class="fa fa-bars"></i></span>
        <div class="container d-row">
            <div class="sidebar-right">
                <ul>
                    <li><button id="add" class="btn btn-green">Add Company/Bills</button></li>
                </ul>
            </div>
            <div class="content">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Payment Date</th>
                                <th>Company<br>Company number</th>
                                <th>Bill Amount</th>
                                <th>Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($records as $record){ ?>
                            <tr id="item-<?php echo $record->id; ?>" data-id="<?php echo $record->id; ?>">
                                <td>
                                    <a class="edit" href="javascript:;"><i class="fa fa-pencil"></i></a>
                                    <a class="delete" href="javascript:;"><i class="fa fa-trash"></i></a>
                                </td>
                                <td><?php echo $record->payment_date; ?></td>
                                <td><?php echo $record->company_name; ?><br><?php echo $record->company_id; ?></td>
                                <td>&euro; <?php echo number_format($record->bill_amount, 2); ?></td>
                                <td><?php echo $record->bill_purpose; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="empty"><?php if (!count($records)) echo 'No records found.'; ?></div>
                </div>
            </div>
        </div>

        <div id="manageBills" class="modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <form action="/create" method="POST">
                    <h2 class="modal-title">Add a Record</h2>
                    <div class="input-group">
                        <label class="req" for="company_name">Company name:</label>
                        <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Name"
                        required>
                        <span class="form-error"></span>
                    </div>
                    <div class="input-group">
                        <label class="req" for="company_id">Company ID:</label>
                        <input type="number" class="form-control" name="company_id" id="company_id" placeholder="ID"
                        required>
                        <span class="form-error"></span>
                    </div>
                    <div class="input-group">
                        <label class="req" for="bill_amount">Bill amount:</label>
                        <input type="number" class="form-control" name="bill_amount" id="bill_amount" placeholder="Amount"
                        required>
                        <span class="form-error"></span>
                    </div>
                    <div class="input-group">
                        <label class="req" for="bill_purpose">Bill purpose:</label>
                        <input type="text" class="form-control" name="bill_purpose" id="bill_purpose" placeholder="Purpose" required>
                        <span class="form-error"></span>
                    </div>
                    <div class="input-group">
                        <label class="req" for="payment_date">Payment date:</label>
                        <input type="date" class="form-control" name="payment_date" id="payment_date" placeholder="Date" required>
                        <span class="form-error"></span>
                    </div>
                    <div class="input-group">
                        <button class="btn btn-green" type="submit">Save</button>
                        <button type="button" class="modal-close ml-auto btn btn-red">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/script.js"></script>
</body>
</html>