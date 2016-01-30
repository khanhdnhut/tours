<?php
if (isset($this->countryBO) && $this->countryBO != NULL) {

    ?>
    <style>
        .form-table th {
            font-weight: 100;   
        }
    </style>
    <h1>
        <?php echo PROFILE_OF_TITLE; ?> <strong><?php
            if (isset($this->countryBO->name)) {
                echo $this->countryBO->name;
            }
            ?></strong>
        <a class="page-title-action" href="#" country="<?php echo $this->countryBO->term_taxonomy_id; ?>" name="<?php echo htmlspecialchars($this->countryBO->name); ?>" onclick="getEditCountryPage(this)"><?php echo DASHBOARD_TOURS_EDIT_COUNTRY_TITLE; ?></a>
    </h1>
    <?php $this->renderFeedbackMessages(); ?>
    <table class="form-table">
        <tbody>
            <tr class="country-name-wrap">
                <th>
                    <label for="name"><?php echo NAME_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->countryBO->name)) {
                        echo htmlspecialchars($this->countryBO->name);
                    }

                    ?>" id="name" name="name">
                </td>
            </tr>
            <tr class="country-slug-wrap">
                <th>
                    <label for="slug"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->countryBO->slug)) {
                        echo htmlspecialchars($this->countryBO->slug);
                    }

                    ?>" id="slug" name="slug">
                </td>
            </tr>
            
            <tr class="country-parent-wrap">
                <th>
                    <label for="parent"><?php echo SLUG_TITLE; ?></label>
                </th>
                <td>
                    <select id="parent" name="parent" disabled="disabled"  >
                        <option value="-1" selected="selected"><?php echo NONE_TITLE; ?></option>                                                
                    </select>
                </td>
            </tr>
            
            <tr class="country-description-wrap">
                <th>
                    <label for="description"><?php echo DESCRIPTION_TITLE; ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" disabled="disabled" value="<?php
                    if (isset($this->countryBO->description)) {
                        echo htmlspecialchars($this->countryBO->description);
                    }

                    ?>" id="description" name="description">
                </td>
            </tr>

            
        </tbody>
    </table>

    <script>
        function getEditCountryPage(element) {
            var country = jQuery(element).attr("country");
            var name = jQuery(element).attr("name");
            var url = "<?php echo URL . CONTEXT_PATH_COUNTRY_EDIT_INFO; ?>" + country + "/" + name;
            if (window.history.replaceState) {
                window.history.replaceState(null, null, url);
            } else if (window.history && window.history.pushState) {
                window.history.pushState({}, null, url);
            } else {
                location = url;
            }
            jQuery.ajax({
                url: "<?php echo URL . CONTEXT_PATH_COUNTRY_EDIT_INFO; ?>",
                type: "POST",
                data: {
                    country: country
                },
                success: function (data, textStatus, jqXHR)
                {
                    jQuery(".wrap").html(data);
                    //data: return data from server
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //if fails      
                }
            });
        }
    </script>
    <?php
} else {
    $this->renderFeedbackMessages();
}

?>
