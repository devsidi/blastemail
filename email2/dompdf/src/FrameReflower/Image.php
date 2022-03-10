��͸wI!E�!f��X�J�ZV���쇱��1�����V�)	QHM�A?�SS]�p;)iV�\����S�>@��V����L��YZ��giw��%�V��a��|x����c�SO >n%֖�jGv�v���q��9g�٣����o����|`�=��8�
a��8��{����k_�,mpVw��Uv6xn��\����Y��쳵�[ej����PۿϚE	�
ei�����Q�21�V�m�Z��c�o+|�(�Veǥ��$Y���"nw`m� A܃��X�U�����m�%��D�[f���:�Z�ed?��Z��HUV��l��8<^v����t���jpd�Z������7X\v�۟q��~Ս��c�X+fl~S�_��U=�|RY�*Lz�W-�������~M���5�������j�>A��,9(c�m~_���gG��a��w�����ߣ|�M<���8���%j?_|׮��+מ�,�vf�i\���x��L�6�m�ZBVe�ڶ�cƤ,��!u��%dm<�I���QTd�֨E�u@�K�0��^�,T��)C���:&;��\ZbVY���C�L9bYڈ�c��q��pi�P�J'O�K��K+�z�N"^�mD6�t��r������\�f�D�̢m���ܿ��5\^�.�z��޷L���li�)܏(Z5w�#L'j�Y����>C����6�j$`�K޶.ȶV�������Z�v���)x�B���y/cr�'.��������O������~LJ?��8���=]ލ�hek��p'-}jB�DȽ��:���Z�6��Y̩�%�7'���WXD�7Ef���v�-���u"oOB�i�k5��%j�+@��Bm"���s�jC��j���Z�����jWBh�q���GF$�4���YM�,RW�SͣB5�6+PZ��VJL����q�
������5Z�ֲ�*k�y�)k^$@_0�jy!R�eD�4���9!�2���j��ĝB�Ƭ���LB�y�$���(�gs�:=���N      //  $page->add_floating_frame($this);
        //}

        // Set the frame's width
        $this->get_min_max_width();

        if ($block) {
            $block->add_frame_to_line($this->_frame);
        }
    }

    /**
     * @return array
     */
    function get_min_max_width()
    {
        if ($this->get_dompdf()->getOptions()->getDebugPng()) {
            // Determine the image's size. Time consuming. Only when really needed?
            list($img_width, $img_height) = Helpers::dompdf_getimagesize($this->_frame->get_image_url(), $this->get_dompdf()->getHttpContext());
            print "get_min_max_width() " .
                $this->_frame->get_style()->width . ' ' .
                $this->_frame->get_style()->height . ';' .
                $this->_frame->get_parent()->get_style()->width . " " .
                $this->_frame->get_parent()->get_style()->height . ";" .
                $this->_frame->get_parent()->get_parent()->get_style()->width . ' ' .
                $this->_frame->get_parent()->get_parent()->get_style()->height . ';' .
                $img_width . ' ' .
                $img_height . '|';
        }

        $style = $this->_frame->get_style();

        $width_forced = true;
        $height_forced = true;

        //own style auto or invalid value: use natural size in px
        //own style value: ignore suffix text including unit, use given number as px
        //own style %: walk up parent chain until found available space in pt; fill available space
        //
        //special ignored unit: e.g. 10ex: e treated as exponent; x ignored; 10e completely invalid ->like auto

        $width = ($style->width > 0 ? $style->width : 0);
        if (Helpers::is_percent($width)) {
            $t = 0.0;
            for ($f = $this->_frame->get_parent(); $f; $f = $f->get_parent()) {
                $f_style = $f->get_style();
                $t = $f_style->length_in_pt($f_style->width);
                if ($t != 0) {
                    break;
                }
            }
            $width = ((float)rtrim($width, "%") * $t) / 100; //maybe 0
        } else {
            // Don't set image original size if "%" branch was 0 or size not given.
            // Otherwise aspect changed on %/auto combination for width/height
            // Resample according to px per inch
            // See also ListBulletImage::__construct
            $width = $style->length_in_pt($width);
        }

        $height = ($style->height > 0 ? $style->height : 0);
        if (Helpers::is_percent($height)) {
            $t = 0.0;
            for ($f = $this->_frame->get_parent(); $f; $f = $f->get_parent()) {
                $f_style = $f->get_style();
                $t = $f_style->length_in_pt($f_style->height);
                if ($t != 0) {
                    break;
                }
            }
            $height = ((float)rtrim($height, "%") * $t) / 100; //maybe 0
        } else {
            // Don't set image original size if "%" branch was 0 or size not given.
            // Otherwise aspect changed on %/auto combination for width/height
            // Resample according to px per inch
            // See also ListBulletImage::__construct
            $height = $style->length_in_pt($height);
        }

        if ($width == 0 || $height == 0) {
            // Determine the image's size. Time consuming. Only when really needed!
            list($img_width, $img_height) = Helpers::dompdf_getimagesize($this->_frame->get_image_url(), $this->get_dompdf()->getHttpContext());

            // don't treat 0 as error. Can be downscaled or can be catched elsewhere if image not readable.
            // Resample according to px per inch
            // See also ListBulletImage::__construct
            if ($width == 0 && $height == 0) {
                $dpi = $this->_frame->get_dompdf()->getOptions()->getDpi();
                $width = (float)($img_width * 72) / $dpi;
                $height = (float)($img_height * 72) / $dpi;
                $width_forced = false;
                $height_forced = false;
            } elseif ($height == 0 && $width != 0) {
                $height_forced = false;
                $height = ($width / $img_width) * $img_height; //keep aspect ratio
            } elseif ($width == 0 && $height != 0) {
                $width_forced = false;
                $width = ($height / $img_height) * $img_width; //keep aspect ratio
            }
        }

        // Handle min/max width/height
        if ($style->min_width !== "none" ||
            $style->max_width !== "none" ||
            $style->min_height !== "none" ||
            $style->max_height !== "none"
        ) {

            list( /*$x*/, /*$y*/, $w, $h) = $this->_frame->get_containing_block();

            $min_width = $style->length_in_pt($style->min_width, $w);
            $max_width = $style->length_in_pt($style->max_width, $w);
            $min_height = $style->length_in_pt($style->min_height, $h);
            $max_height = $style->length_in_pt($style->max_height, $h);

            if ($max_width !== "none" && $width > $max_width) {
                if (!$height_forced) {
                    $height *= $max_width / $width;
                }

                $width = $max_width;
            }

            if ($min_width !== "none" && $width < $min_width) {
                if (!$height_forced) {
                    $height *= $min_width / $width;
                }

                $width = $min_width;
            }

            if ($max_height !== "none" && $height > $max_height) {
                if (!$width_forced) {
                    $width *= $max_height / $height;
                }

                $height = $max_height;
            }

            if ($min_height !== "none" && $height < $min_height) {
                if (!$width_forced) {
                    $width *= $min_height / $height;
                }

                $height = $min_height;
            }
        }

        if ($this->get_dompdf()->getOptions()->getDebugPng()) print $width . ' ' . $height . ';';

        $style->width = $width . "pt";
        $style->height = $height . "pt";

        $style->min_width = "none";
        $style->max_width = "none";
        $style->min_height = "none";
        $style->max_height = "none";

        return array($width, $width, "min" => $width, "max" => $width);
    }
}
