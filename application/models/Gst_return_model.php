<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gst_return_model extends CI_Model
{
	/*

	*/
	public function gstr1($month,$year){
		return $this->db->select('
									i.invoice_no as "InvoiceNo",
									i.invoice_date as "Invoice Date",
									c.first_name as "Customer_Name",
									p.hsn_sac_code as "HSN_Code",
									c.gstid as "Customer_GSTIN",
									c.phone as "Phone",
									(si.price*si.quantity) as "Rate",
									s.discount_value as "DiscountValue",
									(si.cgst_tax) as "CGST",
									(si.sgst_tax) as "SGST",
									(si.igst_tax) as "IGST",
									(si.cgst_tax + si.sgst_tax + si.igst_tax) as "TaxAmount",
									(si.price*si.quantity)+(si.cgst_tax+si.sgst_tax+si.igst_tax)-s.discount_value as "SalesAmount"
								')
						 ->from('sales s')
						 ->join('users c','c.id=s.customer_id')
						 ->join('invoice i','i.sales_id=s.sales_id')
						 ->join('sales_items si','si.sales_id=s.sales_id')
						 ->join('products p','p.product_id=si.product_id')
						 ->where('MONTH(s.date)',$month)
						 ->where('YEAR(s.date)',$year)
						 ->get();
	}
	public function gstr2($month,$year){
		return $this->db->select('
									ac.invoice_no as "InvoiceNo",
									ac.receipt_voucher_date as "Invoice Date",
									"Exclusive" as "Tax Type",
									p.purchase_invoice_number as "Supplier Invoice No",
									s.company as "Supplier_Name",
									CONCAT(cs.name,"-",stc.tin_number) as "State",
									s.gstid as "GSTIN",
									pr.name as "Product_Name",
									pr.hsn_sac_code as "HSN Code",
									pi.quantity as "Quantity",
									pr.unit as "UOM",
									(pi.cost) as "Rate",
									pi.discount_value as "Discount %",
									pi.discount as "Discount",
									(pi.cgst) as "CGST %",
									(pi.cgst_tax) as "CGST",
									(pi.sgst) as "SGST %",
									(pi.sgst_tax) as "SGST",
									(pi.igst) as "IGST %",
									(pi.igst_tax) as "IGST",
									((pi.cost*pi.quantity)+(pi.cgst_tax+pi.sgst_tax+pi.igst_tax)-p.discount_value) as "PurchaseAmount"
								')
						 ->from('purchases p')
						 ->join('users s','s.id = p.supplier_id')
						 ->join('states cs','cs.id = s.state_id')
						 ->join('state_code stc','stc.state_id = cs.id','left')
						 ->join('account_receipts ac','ac.receipt_voucher_no = p.purchase_id')
						 ->join('purchase_items pi','pi.purchase_id = p.purchase_id')
						 ->join('products pr','pr.product_id = pi.product_id')
						 ->where('MONTH(p.date)',$month)
						 ->where('YEAR(p.date)',$year)
						 // ->group_by('ac.invoice_no')
						 ->get();
	}
	public function GSTR1b2b($month,$year){
		
		return $this->db->query('
									SELECT 
										s.reference_no as "Invoice Number",
										DATE_FORMAT(s.date,"%d-%b-%y") as "Invoice date",
										cu.first_name as "Customer Name",
										CONCAT(stc.tin_number,"-",cs.name) as "State",
										cu.gstid as "GSTIN of the Customer",
			 						   	si.product_name as "Product Name",
									   	p.hsn_sac_code as "HSN CODE",
									   	q.uom as "UOM",
									   	si.quantity as "Qty/Nos",
			 						   	si.price as "Sales Rate",
			 						   	si.discount_value as "Discount %",
			 						   	si.discount as "Discount",
			 						   	si.quantity as "Quantity ",
			 						   	si.igst as "IGST %",
			 						   	si.igst_tax as "IGST",
			 						   	si.cgst as "CGST %",
									   	si.cgst_tax as "CGST",
									   	si.sgst as "SGST %",
									   	si.sgst_tax as "SGST",
									   	si.gross_total-si.discount+si.cgst_tax+si.sgst_tax as "Total Value"
									FROM sales_items si
									LEFT JOIN sales s ON s.sales_id = si.sales_id
									LEFT JOIN users cu ON cu.id = s.customer_id
									LEFT JOIN products p ON p.product_id = si.product_id
									LEFT JOIN states cs ON cs.id = cu.state_id
									LEFT JOIN state_code stc ON stc.state_id = cs.id
									LEFT JOIN uqc q ON q.id = p.unit_id
									WHERE MONTH(s.date) = ?
									AND YEAR(s.date) = ?
									AND (cu.gstid <> "")
								',
								array(
										$month,
										$year
									)
								);
	}
	/*

	*/
	public function GSTR1b2cs($month,$year){
		return $this->db->query('
									SELECT 
									(
								   		CASE
								   			WHEN sales_type = 1 THEN "OE"
								   		ELSE "E"
								   		END
								   	) as "Type",
									CONCAT(sc.tin_number,"-",st.name) as "Place Of Supply",
									si.tax_value as Rate,
									(s.total-s.tax_value) as "Taxable Value",
									"0.00" as "Cess Amount",
									cu.gstid as "E-Commerce GSTIN"
									FROM sales s
									INNER JOIN customer cu ON cu.customer_id = s.customer_id 
									INNER JOIN biller b ON b.biller_id = s.biller_id 
									INNER JOIN invoice i ON i.sales_id=s.sales_id
									INNER JOIN states st ON st.id=s.shipping_state_id
									INNER JOIN state_code sc ON sc.state_id=st.id 
									INNER JOIN sales_items si ON si.sales_id=s.sales_id
									WHERE MONTH(i.invoice_date) = ?
									AND YEAR(i.invoice_date) = ?
									AND i.id IN (
										SELECT ii.id 
										FROM customer cc
										INNER JOIN sales ss ON cc.customer_id = ss.customer_id
										INNER JOIN biller bb ON bb.biller_id = ss.biller_id
										INNER JOIN invoice ii ON ii.sales_id = ss.sales_id
										WHERE cc.gst_registration_type = "Unregistered"
										AND 
											IF(ss.shipping_state_id = bb.state_id,1,IF(ss.total-ss.tax_value<250000,1,0))
									)
									GROUP BY i.invoice_no
								',
								array(
										$month,
										$year
									)
								);
	}
	public function GSTR1b2cl($month,$year){
		return $this->db->query('
									SELECT 
										s.reference_no as "Invoice Number",
										DATE_FORMAT(s.date,"%d-%b-%y") as "Invoice date",
										cu.first_name as "Customer Name",
										CONCAT(stc.tin_number,"-",cs.name) as "State",
										cu.gstid as "GSTIN of the Customer",
			 						   	si.product_name as "Product Name",
									   	p.hsn_sac_code as "HSN CODE",
									   	q.uom as "UOM",
									   	si.quantity as "Qty/Nos",
			 						   	si.price as "Sales Rate",
			 						   	si.discount_value as "Discount %",
			 						   	si.discount as "Discount",
			 						   	si.quantity as "Quantity ",
			 						   	si.igst as "IGST %",
			 						   	si.igst_tax as "IGST",
			 						   	si.cgst as "CGST %",
									   	si.cgst_tax as "CGST",
									   	si.sgst as "SGST %",
									   	si.sgst_tax as "SGST",
									   	si.gross_total-si.discount+si.cgst_tax+si.sgst_tax as "Total Value"
									FROM sales_items si
									LEFT JOIN sales s ON s.sales_id = si.sales_id
									LEFT JOIN users cu ON cu.id = s.customer_id
									LEFT JOIN states cs ON cs.id = cu.state_id
									LEFT JOIN state_code stc ON stc.state_id = cs.id
									LEFT JOIN products p ON p.product_id = si.product_id
									LEFT JOIN uqc q ON q.id = p.unit_id
									WHERE MONTH(s.date) = ?
									AND YEAR(s.date) = ?
									AND (cu.gstid IS NULL OR cu.gstid = "")
								',
								array(
										$month,
										$year
									)
								);
		// return $this->db->query('
		// 							SELECT 
		// 							i.invoice_no as "Invoice Number",
		// 							DATE_FORMAT(i.invoice_date,"%d-%b-%y") as "Invoice date",
		// 							i.sales_amount as "Invoice Value",
		// 							CONCAT(sc.tin_number,"-",st.name) as "Place Of Supply",
		// 							si.tax_value as Rate,
		// 							(s.total-s.tax_value) as "Taxable Value",
		// 							"0.00" as "Cess Amount",
		// 							cu.gstid as "E-Commerce GSTIN"
		// 							FROM sales s
		// 							INNER JOIN customer cu ON cu.customer_id = s.customer_id 
		// 							INNER JOIN biller b ON b.biller_id = s.biller_id 
		// 							INNER JOIN invoice i ON i.sales_id=s.sales_id
		// 							INNER JOIN states st ON st.id=s.shipping_state_id
		// 							INNER JOIN state_code sc ON sc.state_id=st.id 
		// 							INNER JOIN sales_items si ON si.sales_id=s.sales_id
		// 							WHERE MONTH(i.invoice_date) = ?
		// 							AND YEAR(i.invoice_date) = ?
		// 							AND i.id IN (
		// 								SELECT ii.id 
		// 								FROM customer cc
		// 								INNER JOIN sales ss ON cc.customer_id = ss.customer_id
		// 								INNER JOIN biller bb ON bb.biller_id = ss.biller_id
		// 								INNER JOIN invoice ii ON ii.sales_id = ss.sales_id
		// 								WHERE cc.gst_registration_type = "Unregistered"
		// 								AND 
		// 									IF(ss.shipping_state_id != bb.state_id,IF(ss.total-ss.tax_value>250000,1,0),0)
		// 							)
		// 							GROUP BY i.invoice_no

		// 						',
		// 						array(
		// 								$month,
		// 								$year
		// 							)
		// 						);
	}
	public function GSTR1cdnr($month,$year){
		return $this->db->query('
									SELECT 
									b.gstid as "GSTIN/UIN of Recipient",
									i.invoice_no as "Invoice/Advance Receipt Number",
									DATE_FORMAT(i.invoice_date,"%d-%b-%y") as "Invoice/Advance Receipt date",
									cd.note_or_refund_voucher_no as "Note/Refund Voucher Number",
									DATE_FORMAT(cd.note_or_refund_voucher_date,"%d-%b-%y") as "Note/Refund Voucher Date",
									cd.document_type as "Document Type",
									(
								   		CASE
								   			WHEN cd.reason_for_issue_document = 2 THEN "02-Post Sale Discount"
								   			WHEN cd.reason_for_issue_document = 3 THEN "03-Dificiency in Service"
								   			WHEN cd.reason_for_issue_document = 4 THEN "04-Correction of Invoice"
								   			WHEN cd.reason_for_issue_document = 5 THEN "05-Change in POS"
								   			WHEN cd.reason_for_issue_document = 6 THEN "06-Finalization of Provisional assessment"
								   			WHEN cd.reason_for_issue_document = 7 THEN "07-Others"
								   		ELSE "01-Sales Retun"
								   		END
								   	) as "Reason For Issuing document",
								   	CONCAT(sc.tin_number,"-",st.name) as "Place Of Supply",
								   	cd.note_or_refund_voucher_value as "Note/Refund Voucher Value",
								   	si.tax_value as Rate,
								   	(s.total-s.tax_value) as "Taxable Value",
									"0.00" as "Cess Amount",
									(
								   		CASE
								   			WHEN cd.pre_gst = "Y" THEN "Y"
								   		ELSE "N"
								   		END
								   	) as "Pre GST"
									FROM sales s
									INNER JOIN customer cu ON cu.customer_id = s.customer_id 
									INNER JOIN biller b ON b.biller_id = s.biller_id 
									INNER JOIN invoice i ON i.sales_id=s.sales_id
									INNER JOIN credit_debit_note cd ON cd.invoice_id=i.id
									INNER JOIN states st ON st.id=s.shipping_state_id
									INNER JOIN state_code sc ON sc.state_id=st.id 
									INNER JOIN sales_items si ON si.sales_id=s.sales_id
									WHERE cu.gst_registration_type = "Registered"
									AND MONTH(i.invoice_date) = ?
									AND YEAR(i.invoice_date) = ?
									GROUP BY i.invoice_no

								',
								array(
										$month,
										$year
									)
								);
	}
	public function GSTR1cdnur($month,$year){
		return $this->db->query('
									SELECT 
									(
								   		CASE
								   			WHEN sales_invoice = 1 THEN "B2CL"
								   			WHEN sales_invoice = 2 THEN "EXPWP"
								   			WHEN sales_invoice = 3 THEN "EXPWOP"
								   		ELSE ""
								   		END
								   	) as "UR Type",
									cd.note_or_refund_voucher_no as "Note/Refund Voucher Number",
									DATE_FORMAT(cd.note_or_refund_voucher_date,"%d-%b-%y") as "Note/Refund Voucher Date",
									cd.document_type as "Document Type",
									i.invoice_no as "Invoice/Advance Receipt Number",
									DATE_FORMAT(i.invoice_date,"%d-%b-%y") as "Invoice/Advance Receipt date",
									(
								   		CASE
								   			WHEN cd.reason_for_issue_document = 2 THEN "02-Post Sale Discount"
								   			WHEN cd.reason_for_issue_document = 3 THEN "03-Dificiency in Service"
								   			WHEN cd.reason_for_issue_document = 4 THEN "04-Correction of Invoice"
								   			WHEN cd.reason_for_issue_document = 5 THEN "05-Change in POS"
								   			WHEN cd.reason_for_issue_document = 6 THEN "06-Finalization of Provisional assessment"
								   			WHEN cd.reason_for_issue_document = 7 THEN "07-Others"
								   		ELSE "01-Sales Retun"
								   		END
								   	) as "Reason For Issuing document",
								   	CONCAT(sc.tin_number,"-",st.name) as "Place Of Supply",
								   	cd.note_or_refund_voucher_value as "Note/Refund Voucher Value",
								   	si.tax_value as Rate,
								   	(s.total-s.tax_value) as "Taxable Value",
									"0.00" as "Cess Amount",
									(
								   		CASE
								   			WHEN cd.pre_gst = "Y" THEN "Y"
								   		ELSE "N"
								   		END
								   	) as "Pre GST"
									FROM sales s
									INNER JOIN customer cu ON cu.customer_id = s.customer_id 
									INNER JOIN biller b ON b.biller_id = s.biller_id 
									INNER JOIN invoice i ON i.sales_id=s.sales_id
									INNER JOIN credit_debit_note cd ON cd.invoice_id=i.id
									INNER JOIN states st ON st.id=s.shipping_state_id
									INNER JOIN state_code sc ON sc.state_id=st.id 
									INNER JOIN sales_items si ON si.sales_id=s.sales_id
									WHERE cu.gst_registration_type = "Unregistered"
									AND 
										IF(s.shipping_state_id != b.state_id,IF(s.total-s.tax_value>250000,1,0),0)
									AND MONTH(i.invoice_date) = ?
									AND YEAR(i.invoice_date) = ?
									GROUP BY i.invoice_no

								',
								array(
										$month,
										$year
									)
								);
	}
	public function GSTR1exp($month,$year){
		return $this->db->query('
									SELECT 
									(
								   		CASE
								   			WHEN sales_invoice = 2 THEN "WPAY"
								   			WHEN sales_invoice = 3 THEN "WOPAY"
								   			WHEN sales_invoice = 4 THEN "WOPAY"
								   		END
								   	) as "Export Type",
								   	i.invoice_no as "Invoice Number",
								   	DATE_FORMAT(i.invoice_date,"%d-%b-%y") as "Invoice date",
								   	(s.total) as "Invoice Value",
								   	s.port_code as "Port Code",
								   	s.shipping_bill_no as "Shipping Bill Number",
								   	DATE_FORMAT(s.shipping_bill_date,"%d-%b-%y") as "Shipping Bill Date",
								   	si.tax_value as Rate,
									(s.total-s.tax_value) as "Taxable Value"
									FROM sales s
									INNER JOIN customer cu ON cu.customer_id = s.customer_id 
									INNER JOIN biller b ON b.biller_id = s.biller_id 
									INNER JOIN invoice i ON i.sales_id=s.sales_id
									INNER JOIN states st ON st.id=s.shipping_state_id
									INNER JOIN state_code sc ON sc.state_id=st.id 
									INNER JOIN sales_items si ON si.sales_id=s.sales_id
									WHERE s.sales_invoice != 1
									AND MONTH(i.invoice_date) = ?
									AND YEAR(i.invoice_date) = ?
									GROUP BY i.invoice_no

								',
								array(
										$month,
										$year
									)
								);
	}
	public function GSTR1exemp($month,$year){
		/*return $this->db->query('
				SELECT * FROM test 
			');*/

		/*return $this->db->query('
									SELECT 
										
										"Inter-State Supplies to registerd persons" as "Description",  
										(SELECT sum(Total) FROM  o_r_non_gst )  as "Non GST",
										(SELECT sum(Total) FROM  o_r_nill_rated)  as "Nill Rated",
										(SELECT sum(Total) FROM  o_r_exempted)  as "Exempted" 
								');*/
		/*return $this->db->query('
						SELECT * FROM 
						(
							(SELECT * FROM r1) UNION
							(SELECT * FROM r2) UNION
							(SELECT * FROM r3) UNION
							(SELECT * FROM r4) 
						) as u
				');*/
		return $this->db->query('
		SELECT * FROM
		(
			SELECT 
			"Inter-State Supplies to registerd persons" as "Description", 
			(
				SELECT 
				   	COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 3
				AND cu.gst_registration_type = "Registered"
				AND b.state_id = s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Nil Rated Supplies",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 4
				AND cu.gst_registration_type = "Registered"
				AND b.state_id = s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Exempted(other than nil rated/non GST supply)",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 2
				AND cu.gst_registration_type = "Registered"
				AND b.state_id = s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Non-GST supplies"
		) as r1
		UNION
		SELECT * FROM
		(
			SELECT 
			"Intra-State Supplies to registerd persons" as "Description", 
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 3
				AND cu.gst_registration_type = "Registered"
				AND b.state_id != s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Nil Rated Supplies",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 4
				AND cu.gst_registration_type = "Registered"
				AND b.state_id != s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Exempted(other than nil rated/non GST supply)",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 2
				AND cu.gst_registration_type = "Registered"
				AND b.state_id != s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Non-GST supplies"
		) as r2
		UNION
		SELECT * FROM
		(
			SELECT 
			"Inter-State Supplies to unregisterd persons" as "Description", 
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 3
				AND cu.gst_registration_type = "unregistered"
				AND b.state_id = s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Nil Rated Supplies",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 4
				AND cu.gst_registration_type = "unregistered"
				AND b.state_id = s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Exempted(other than nil rated/non GST supply)",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 2
				AND cu.gst_registration_type = "unregistered"
				AND b.state_id = s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Non-GST supplies"
		) as r3
		UNION
		SELECT * FROM
		(
			SELECT 
			"Intra-State Supplies to unregisterd persons" as "Description", 
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 3
				AND cu.gst_registration_type = "unregistered"
				AND b.state_id != s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Nil Rated Supplies",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 4
				AND cu.gst_registration_type = "unregistered"
				AND b.state_id != s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Exempted(other than nil rated/non GST supply)",
			(
				SELECT 
					COALESCE(SUM(s.total),0)
				FROM  sales s
				INNER JOIN tax t ON t.tax_id = s.tax_id
				INNER JOIN customer cu ON cu.customer_id = s.customer_id 
				INNER JOIN biller b ON b.biller_id = s.biller_id 
				INNER JOIN invoice i ON i.sales_id=s.sales_id
				INNER JOIN states st ON st.id=s.shipping_state_id
				INNER JOIN state_code sc ON sc.state_id=st.id 
				WHERE t.tax_type = 2
				AND cu.gst_registration_type = "unregistered"
				AND b.state_id != s.shipping_state_id
				AND MONTH(i.invoice_date) = ?
				AND YEAR(i.invoice_date) = ?
			) as "Non-GST supplies"
		) as r2
					

				
		
		',
		array(
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year,
				$month,
				$year
			)
		);
		/*return $this->db->query('
									SELECT * FROM
									(
										(
											SELECT sum(sales1.Total) as "Total" FROM o_r_non_gst as sales1
										)
										UNION
										(SELECT 
											sum(sales2.Total) as "Total"
											FROM 
											 o_r_nill_rated as sales2)
										UNION
										(SELECT 
											sum(sales3.Total) as "Total"
											FROM 
											 o_r_exempted as sales3)
									) as u

								');*/
		/*return $this->db->query('
									SELECT * FROM(
										(SELECT * FROM  o_r_non_gst)
									)
			');*/
	}
	public function GSTR1hsn($month,$year){
		return $this->db->query('
									SELECT 
										pr.hsn_sac_code as "HSN",
										pr.name as "Description",
										pr.unit as "UQC",
										sum(si.quantity) as "Total Quantity",
										sum(si.gross_total-si.discount+si.tax) as "Total Value",
										sum(si.gross_total-si.discount) as "Taxable Value",
										(
											CASE
												WHEN s.shipping_state_id != b.state_id THEN TRUNCATE(sum(si.tax),2)
												ELSE 0
											END
										) as "Integrated Tax Amount",
										(
											CASE
												WHEN s.shipping_state_id = b.state_id THEN TRUNCATE(sum(si.tax)/2,2)
												ELSE 0
											END
										) as "Central Tax Amount",
										(
											CASE
												WHEN s.shipping_state_id = b.state_id THEN TRUNCATE(sum(si.tax)/2,2)
												ELSE 0
											END
										) as "State/UT Tax Amount",
										"0.00" as "Cess Amount"
									FROM sales s
									INNER JOIN customer cu ON cu.customer_id = s.customer_id 
									INNER JOIN biller b ON b.biller_id = s.biller_id 
									INNER JOIN invoice i ON i.sales_id=s.sales_id
									INNER JOIN states st ON st.id=s.shipping_state_id
									INNER JOIN state_code sc ON sc.state_id=st.id 
									INNER JOIN sales_items si ON si.sales_id=s.sales_id
									INNER JOIN products pr ON pr.product_id = si.product_id
									AND MONTH(i.invoice_date) = ?
									AND YEAR(i.invoice_date) = ?
									GROUP BY pr.hsn_sac_code

								',
								array(
										$month,
										$year
									)
								);
	}
}
?>