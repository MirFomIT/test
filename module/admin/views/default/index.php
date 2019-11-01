<div class="admin-default-index">
    <h3>Не активированные пользователи</h3>
    <table class="table table-striped table-bordered" id = "table">
        <thead>
        <tr>
            <th class="col-xs-1 col-sm-1" scope="col">№</th>
            <th class="col-xs-3 col-sm-3" scope="col">номер карты</th>
            <th class="col-xs-3 col-sm-2" scope="col">телефон</th>
            <th class="col-xs-2 col-sm-2" scope="col">пин код</th>
        </tr>
        </thead>
        <tbody>
        <?php  $number = 1; foreach ($not_active_cards as $not_active_card):?>

            <tr id="tr_<?= $not_active_card->id?>">
                <td data-label=""><?= $number?></td>
                <td data-label="номер карты"><?= $not_active_card->number?></td>
                <td data-label="телефон"><?= $not_active_card->phone?></td>
                <td data-label="пин код"><?= $not_active_card->pin?></td>
            </tr>
            <?php $number++?>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
