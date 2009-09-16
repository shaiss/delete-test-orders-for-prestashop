<?php
/**
 * Copyright (c) 2009, Shai Perednik, CSTS.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *	* Redistributions of source code must retain the above copyright
 *	  notice, this list of conditions and the following disclaimer.
 *
 *	* Redistributions in binary form must reproduce the above
 *	  copyright notice, this list of conditions and the following
 *	  disclaimer in the documentation and/or other materials provided
 *	  with the distribution.
 *
 *	* Neither the names of Shai Perednik or CSTS.com, nor
 *	  the names of its contributors may be used to endorse or promote
 *	  products derived from this software without specific prior
 *	  written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY
 * WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
 * OF SUCH DAMAGE.
 */

/*
 * This is a BSD License approved by the Open Source Initiative (OSI).
 * See:  http://www.opensource.org/licenses/bsd-license.php
 */
?>

<?php


class DeleteTestOrders extends Module
{
	private $_html = '';
	private $_postErrors = array();
	//private $fb;

	function __construct()
	{
		$this->name = 'deletetestorders';
		parent::__construct();

		$this->tab = 'Tools';
		$this->version = '1.0';
		$this->displayName = $this->l('Delete Test Orders');
		$this->description = $this->l('Use this to delete all the test orders placed prior to oppening the shop');
	}

	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName . " " . $this->version .'</h2>';
		if (Tools::isSubmit('submit'))
		{
			$this->_postValidation();
	
			if (!sizeof($this->_postErrors))
			{
				$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('OK').'" /> '.$this->l('All Orders Deleted').'</div>';
			}
			else
			{
				foreach ($this->_postErrors AS $err)
				{
					$this->_html .= '<div class="alert error">'.$err.'</div>';
				}
			}
		}
	
		$this->_displayModInfo();
		$this->_displayForm();
		$this->_displayInstructions();
		$this->_displayCredits();
		$this->_displayDonations();
		$this->_displayLicense();
	
		return $this->_html;
		

	}
	
	private function _postValidation()
	{
		if ($_POST['i_am_sure'] != '')
		{
			$this->DeleteOrders();
		}
		else
		{
			$this->_postErrors[] = $this->l('Box below not checked.  Nothing Deleted');
		}
	}

	private function _displayModInfo()
	{
		$this->_html .= '<img src="../modules/'.$this->name.'/logo.gif" style="float:left; margin-right:15px;"><b>'.$this->l('Use this to delete all the test orders placed prior to oppening the shop.').'</b><br /><br />';
	}

	private function _displayForm()
	{
		$this->_html .= '
		<fieldset>
		<legend><img src="../modules/' . $this->name. '/question.gif" />'.$this->l('Are you Sure?').'</legend>
		<table border="0" width="500" cellpadding="0" cellspacing="0" id="form">
		
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
				
				<!--Are you Sure TickBOX-->
				<div class="margin-form">
					'.$this->l('Delete ALL Orders!').'
					<input type="checkbox" name="i_am_sure" value="delete" />
				</div>				
				<input type="submit" name="submit" value="'.$this->l('Submit').'" class="button" />
		</form>
		</table>
		</fieldset>
		';
	}

	private function _displayInstructions()
	{
		$this->_html .= '<br><fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Instructions').'</legend>
			<b><font size="5" color="red">!!!WARNING!!!  USE WITH CAUTION</font></b></large><br><br>
			If you check the box above, <b>ALL ORDERS</b> will be deleted executing the following MySql queries:
			<ul>
			<li>TRUNCATE `ps_orders`;</li>
			<li>TRUNCATE `ps_order_customization_return`;</li>
			<li>TRUNCATE `ps_order_detail`;</li>
			<li>TRUNCATE `ps_order_discount`;</li>
			<li>TRUNCATE `ps_order_history`;</li>
			<li>TRUNCATE `ps_order_message`;</li>
			<li>TRUNCATE `ps_order_message_lang`;</li>
			<li>TRUNCATE `ps_order_return`;</li>
			<li>TRUNCATE `ps_order_return_detail`;</li>
			<li>TRUNCATE `ps_order_return_state`;</li>
			<li>TRUNCATE `ps_order_return_state_lang`;</li>
			<li>TRUNCATE `ps_order_slip`;</li>
			<li>TRUNCATE `ps_order_slip_detail`;</li>
			<li>TRUNCATE `ps_message`;</li>
			<li>TRUNCATE `ps_cart`;</li>
			<li>TRUNCATE `ps_cart_product`;</li>
			</ul>
			</fieldset>';
		
	}
	
	private function _displayCredits()
	{
		$this->_html .= '<br><fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Credits').'</legend>
				Many thanks to the PrestaShop Team and Community for assisting in creating this <a href="http://www.prestashop.com/forums/viewthread/23408/modules/serial_number_module/">module</a>.<br>
				In particular, I would like to thank <a href="http://www.prestashop.com/forums/member/11264/paul_c/">Paul</a>
				from <a href="eCartService.net"><img alt="eCartService.net" 
				src="../modules/'.$this->name.'/ecart_inv.resized.jpg">(eCartService.net)</a>.  For without his guides and help this module would not be possible.
			</fieldset>';
			
	}
	
	private function _displayDonations()
	{
		$this->_html .= '<br><fieldset>
			<legend><img src="../img/admin/dollar.gif" />'.$this->l('Donations').'</legend>
				Lots of time was spent developing this module.  If you are using it, please consider donating<br><br>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="7323531">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>

			</fieldset>';
			
	}
	
	private function _displayLicense()
	{
		$this->_html .= '<br><fieldset>
			<legend><img src="../modules/editorial/logo.gif" />'.$this->l('License').'</legend>
				<small><small>Copyright &copy 2009, Shai Perednik, <a href="http://csts.com">CSTS.com</a>.<br>
				All rights reserved.<br>
				<br>
				Redistribution and use in source and binary forms, with or without
				modification, are permitted provided that the following conditions are
				met:<br>
				<br>
				Redistributions of source code must retain the above copyright notice,
				this list of conditions and the following disclaimer.<br>
				<br>
				Redistributions in binary form must reproduce the above copyright
				notice, this list of conditions and the following disclaimer in the
				documentation and/or other materials provided with the distribution.<br>
				<br>
				Neither the names of Shai Perednik or CSTS.com, nor the
				names of its contributors may be used to endorse or promote products
				derived from this software without specific prior written permission.<br>
				<br>
				THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
				IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
				TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
				PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
				OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
				SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
				LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
				DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
				THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
				(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
				OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.<br>
				<br>
				This is a BSD License approved by the Open Source Initiative (OSI).<br>
				See:&nbsp; <a href="http://www.opensource.org/licenses/bsd-license.php">http://www.opensource.org/licenses/bsd-license.php</a></small></small>
			</fieldset>';
	}

	function install()
	{
		if (!parent::install()
			)
			return false;
		return true;
	}
	
	function uninstall()
	{
		if (!parent::uninstall()
				OR !$this->uninstallDB()
			)
			return false;
		return true;
	}
	
	function DeleteOrders()
	{
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "orders`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_customization_return`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_detail`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_discount`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_history`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_message`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_message_lang`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_return`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_return_detail`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_return_state`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_return_state_lang`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_slip`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "order_slip_detail`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "message`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "cart`");
		Db::getInstance()->ExecuteS("TRUNCATE `" . _DB_PREFIX_ . "cart_product`");
		
		return true;
		
	}
}

?>