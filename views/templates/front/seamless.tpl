<form class="payment-form-seamless" data-id="{$id}" data-integration-key="{$integrationKey}" method="POST" action="{$action}">
    <input type="hidden" name="ccEmail" value="">
    <div>
        <div id="payment-error-{$id}" class="alert alert-warning" style="display: none;">
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label">Firstname</label>
                <div class="">
                    <input type="text" class="form-control" name="ccFirstName" id="till-payments-ccFirstName-{$id}"/>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label">Lastname</label>
                <div class="">
                    <input type="text" class="form-control" name="ccLastName" id="till-payments-ccLastName-{$id}"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-8">
                <label class="form-control-label">Card Number</label>
                <div class="form-control" id="till-payments-ccCardNumber-{$id}" style="padding: 0; overflow: hidden"></div>
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">CVV</label>
                <div class="form-control" id="till-payments-ccCvv-{$id}" style="padding: 0; overflow: hidden"></div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2">
                <label class="form-control-label">Month</label>
                <div class="">
                    <select class="form-control" name="ccExpiryMonth" id="till-payments-ccExpiryMonth-{$id}">
                        {foreach from=$months item=month}
                            <option value="{$month}">{$month}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label class="form-control-label">Year</label>
                <div class="">
                    <select class="form-control" name="ccExpiryYear" id="till-payments-ccExpiryYear-{$id}">
                        {foreach from=$years item=year}
                            <option value="{$year}">{$year}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
