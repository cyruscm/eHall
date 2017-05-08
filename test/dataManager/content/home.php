
              <ul class="collapsible" data-collapsible="accordion">
                <li>
                  <div class="collapsible-header" style="padding:30px;">Submit a new message</div>
                  <div class="collapsible-body">
                    <div class="row" style="margin:10px">
                      <form class="col s12" method="POST" action="process.php">
                        <div class="row">
                          <div class="input-field col s12">
                            <label style="font-size:16px">For</label><br><br>
                            <select class="js-example-basic-single validate" style="width:100%">
                              <optgroup label="Teachers">
                                <option value="1">Teacher 1</option>
                                <option value="2">Teacher 2</option>
                                <option value="3">Teacher 3</option>
                                <option value="4">Teacher 4</option>
                                <option value="5">Teacher 5</option>
                              </optgroup>
                              <optgroup label="Administrators">
                                <option value="1">Admin 1</option>
                                <option value="2">Admin 2</option>
                                <option value="3">Admin 3</option>
                                <option value="4">Admin 4</option>
                                <option value="5">Admin 5</option>
                                <option value="6">Admin 6</option>
                              </optgroup>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12">
                            <input id="from" type="text" class="validate">
                            <label for="from">From</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12 m6">
                            <input id="phone" type="tel" class="validate">
                            <label for="phone">Phone</label>
                          </div>
                          <div class="input-field col s12 m6">
                            <input id="cell" type="tel" class="validate">
                            <label for="cell">Cellphone</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12 m6">
                            <textarea id="message" class="materialize-textarea"></textarea>
                            <label for="message">Message</label>
                          </div>
                          <div class="col s12 m6">
                            <label style="font-size:16px">Type</label><br><br>
                              <input name="type" type="radio" id="type1" />
                              <label for="type1">Red</label>
                              <br />
                              <input name="type" type="radio" id="type2" />
                              <label for="type2">Yellow</label>
                              <br />
                              <input name="type" type="radio" id="type3" />
                              <label for="type3">Filled in</label>
                              <br />
                              <input name="type" type="radio" id="type4" />
                              <label for="type4">Green</label>
                            </p>
                          </div>
                        </div>
                        <div class="right" style="margin-top:-30px">
                          <button style="margin-top:-30px" type="submit" class="waves-effect waves-light btn">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </li>
              </ul>
<script>
$(document).ready(function() {
  $(".js-example-basic-single").select2();
});
</script>