module main;
    reg CLK;
	
  	reg RESET, readEn, writeEn;
	wire busy;
	reg[7:0]  address,dataIn;
	wire[7:0] dataOut;
 
	cache myCache(CLK,RESET,readEn,writeEn, address,dataIn,dataOut,busy);
 
    initial begin

		#0
		$dumpfile("myCache.vcd"); 
		$dumpvars(0, myCache);
		
		CLK <= 0;
		RESET <= 0;
		
        #10
		readEn <= 0;
		writeEn <=1;
		assign address =0;// index-0, tag-0, block-0
		assign dataIn =10;
		
		#20
		readEn <= 1;
		writeEn <=0;
		assign address = 0;// index-0, tag-0, block-1
  
		#30
		readEn <= 0;
		writeEn <=1;
  	
		assign address = 2; // index-1, tag-0, block-0
        assign dataIn =30;

		#40
		readEn <= 0;
		writeEn <=1;
		assign address = 16; // index-0, tag-1, block-0
        assign dataIn = 40;		
		
		#50
		readEn <= 1;
		writeEn <=0;
		assign address = 0; // index-0, tag-1, block-0
   		
		#60
		readEn <= 1;
		writeEn <=0;
		assign address = 2; // index-0, tag-1, block-0
   		
		#70
		readEn <= 1;
		writeEn <=0;
		assign address = 16; // index-0, tag-1, block-0
   		
	
		#100
        $finish;
    end
    
    always begin
		#5 CLK=~CLK;     // Clock is 2
		$display(""); 
	end
	
endmodule

module cache(
	CLK,RESET,
	readEn,writeEn,		// read and write enable data bits 
	address,			// read/write address
	dataIn,		// write the data to cache from processor
	dataOut,		// send data to the processor
	busy);	// wait flag)

	// Processor to Cache Memory
	input       CLK, RESET;
	input			readEn,writeEn;
	input[7:0]      address, dataIn;
	output[7:0]     dataOut;
	output	busy;
	input busyM;
	reg[7:0] dataOut;
	
	// Cache to Data Memory
	
	wire [15:0] dataMIn;
	reg [15:0] dataMOut;
	
	reg writeM, readM;
	reg[6:0] dataMAddress;

	// data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);
	data_mem myMem(CLK, RESET, readM, writeM, dataMAddress, dataMIn, dataMOut, busyM);
	
	// Cache special variables
	reg [3:0] TAG [7:0];
	reg V [7:0];					// Valid Bit
	reg D [7:0];					// Dirty Bit
	reg busy;
	
	reg [15:0] cacheData[7:0];
	reg [7:0] memory[255:0];
	
	wire offset;
	wire[3:0] tag;
	wire[2:0] index;
	
	integer i;	

	assign offset = address[0];
	assign tag = address[7:4];
	assign index = address[3:1];

	// Reset the cache data on RST = 1 
	always @(posedge RESET) begin
		if (RESET) begin
			busy<= 0;
			for (i=0;i<8; i=i+1)
				cacheData[i] <= 0;
		end
	end
	
	// initial status, set all registers to 0
	initial begin
		for (i=0;i<8; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 1'b0;
			V[i] <= 1'b0;
			D[i] <= 1'b0;
			
		end
		busy <= 1'b0;
	end
	
	// on each clock cycle
	always @(*) begin	// negedge CLK
			// --- READ ----------
			if(!writeEn && readEn && !busy) begin
				// Need to send the requested data into processor     {  cache  } ---> Processor 				
				busy <= 1'b1;
			
				if (TAG[index]==tag && V[index]==1) begin
					// tag match and valid ---> HIT, 			no need any process
					$display("\t HIT on the [ %b | %b | x ] V[%b] D[%b]", TAG[index], index, V[index], D[index]);
					
				end else if (TAG[index]==tag && V[index]==0) begin
						// tag match, but not valid -> Compulsory MISS, 	need to load the data from main memory
						$display("\t MISS on the [ %b | %b | x ] V[%b] D[%b] \t <loaded the data because it is  not valid>", TAG[index], index, V[index], D[index]);
					
						// --- Load -----------------
						//cacheData[index][7:0] <= memory[address[7:1] + 8'b00000000];
						//cacheData[index][15:8] <= memory[address[7:1] + 8'b00000001]; 
						writeM <=0;
						readM <=1;
						dataMAddress <= address[7:1];
						
						// Wait until main memory sends the requested data 
						while(busyM==1'b0)
							#5 $display("waiting...(0)");
						while(busyM==1'b1)
							#5 $display("waiting...(1)");
						
						#1
						cacheData[index] <= dataMIn; 
						writeM <=0;
						readM <=0;
						
						// ---------------------------
						
						TAG[index] <= tag;	
						V[index] <= 1'b1;
					
				end else begin
					// tag isn't match --> Conflict MISS
					
					if (D[index]==1) begin
						// Write back the current block into the data memory
						
						// --- Write-----------------
						//memory[ (TAG[index]*16) + (index*2) + 0] <= cacheData[index][7:0];
						//memory[ (TAG[index]*16) + (index*2) + 1] <= cacheData[index][15:8];
						
						writeM <=1;
						readM <=0;
						
						dataMAddress <= address[7:1];
						dataMOut <= cacheData[index];
						
						// Wait until main memory writes the given data
						while(busyM==1'b0)
							#5 $display("waiting...(0)");
						while(busyM==1'b1)
							#5 $display("waiting...(1)");
							
						#1
						writeM <=0;
						readM <=0;
						
						// ---------------------------
						
						D[index] <= 1'b0;
						$display("\t MISS on the [ %b | %b | x ] V[%b] D[%b] \t <write back the data into main memory>", TAG[index], index, V[index], D[index]);
				
					end 
					
					// --- Read -----------------
					//cacheData[index][7:0] <= memory[address[7:1] + 8'b00000000 ];
					//cacheData[index][15:8] <= memory[address[7:1] + 8'b00000001]; 
					
					writeM <=0;
					readM <=1;
						
					dataMAddress <= address[7:1];
					
					// Wait until main memory sends the requested data
					while(busyM==1'b0)
						#5 $display("waiting...(0)");
					while(busyM==1'b1)
						#5 $display("waiting...(1)");
					
					#1
					cacheData[index] <= dataMIn; 
					writeM <=0;
					readM <=0;

					// ---------------------------
					
					TAG[index] <= tag;	
					V[index] <= 1'b1;
				
				end
				
				// -- Send the required data to the processor ----
				if (offset == 0) begin
						dataOut <= cacheData[index][7:0];
				end else begin
						dataOut <= cacheData[index][15:8];
				end
				// ------------------------------------------------------------
		
				$display("Read %d from  %d", dataOut, index);
				busy <= 1'b0;
			end
			
			// ----- WRITE --------
			if (writeEn && !readEn && !busy) begin
				busy <= 1'b1;
				
				if (TAG[index]==tag && V[index]==1) begin
					// tag match and valid ---> HIT, 			just write
					$display("\t HIT on the [ %b | %b | %b ] V[%b] D[%b] \t <tag match, valid>", TAG[index], index, address[0],  V[index], D[index]);
					D[index] <= 1'b1;
					
				end else if (TAG[index]==tag && V[index]==0) begin
					// tag match but not valid ---> HIT, 		just write 
					$display("\t HIT on the [ %b | %b | %b ] V[%b] D[%b] \t <tag match, not valid>", TAG[index], index, address[0],  V[index], D[index]);
					V[index] <= 1'b1;
					D[index] <= 1'b1;
					
				end else begin
					// tag not match ---> MISS			
					
					if (D[index]==1) begin
						// Write back the current block into the data memory
						$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <write back>", TAG[index], index,  address[0], V[index], D[index]);
						
						// --- Write  Back ------
						//memory[ (TAG[index]*16) + (index*2) + 0] <= cacheData[index][7:0];
						//memory[ (TAG[index]*16) + (index*2) + 1] <= cacheData[index][15:8];
							
						writeM <=1;
						readM <=0;
						
						#1
						dataMAddress <= address[7:1];
						dataMOut <= cacheData[index];
						
						// Wait until main memory sends the requested data
						while(busyM==1'b0)
							#5 $display("waiting...(0)");
						while(busyM==1'b1)
							#5 $display("waiting...(1)");
												
						writeM <=0;
						readM <=0;
						
						// ---------------------------
						
					end 
				end
				
				// write the data into cache and make dirty bit=1, valid bit=1
				#1
				cacheData[index] <= dataIn;
				TAG[index] <= tag;
				D[index] <= 1'b1;
				V[index] <= 1'b1;
				
				$display("Written %d into  %d", dataIn, index);
				busy <= 1'b0;
			end
	end
endmodule


module data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);

	input           	CLK, RESET, readEn, writeEn;
	input [6:0] 	dataAddress;
	input [15:0] dataIn;
	output [15:0] 	dataOut;
	output			BUSY;

	reg[15:0]     	dataOut;
	reg BUSY;
	
	integer  i;

	reg [7:0] memory[255:0];

	// reset the memory values @ positive edge of the RESET signal
	always @(posedge RESET) begin
			BUSY<= 0;
			for (i=0;i<256; i=i+1)
				memory[i] <= 0;
	end

	always @(posedge CLK) begin
			// Reading data from the memory
			if(!writeEn && readEn && !BUSY) begin
				BUSY <= 1;
				#1000;  // Clock * Cycles
				dataOut <= (memory[dataAddress*2 + 0 ]*256) + memory[dataAddress*2 + 1];
				$display ("Read %d from  %d", dataOut, dataAddress);
				BUSY <= 0;
				
				//for (i=0;i<16; i=i+1)
				//	$display ("memory[ %d ] = %b", i, memory[i]);
					
			end
	end 
	
	always @(posedge CLK) begin
			if (writeEn && !readEn && !BUSY) begin
				BUSY <= 1;
				#1000;  // Clock * Cycles
				memory[dataAddress*2 + 0 ] <= dataIn[15:8];
				memory[dataAddress*2 + 1 ] <= dataIn[7:0];
				
				$display ("Written %d into  %d", dataIn, dataAddress);
				BUSY <= 0;
				
				//for (i=0;i<16; i=i+1)
				//	$display ("memory[ %d ] = %b", i, memory[i]);
					
			end
	end
	
	initial begin
	    // Initialize the memory
       	BUSY<= 0;
		for (i=0;i<256; i=i+1)
			memory[i] <= 0;
	end
	
endmodule



