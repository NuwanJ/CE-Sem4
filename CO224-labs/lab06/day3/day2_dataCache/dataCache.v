module main;
    reg CLK;
	
  	reg RESET, readEn, writeEn;
	wire busy;
	reg[7:0]  address, dataIn;
	wire[7:0] dataOut;
 
	dataCache myDataCache(CLK,RESET,readEn,writeEn, address,dataIn,dataOut,busy);
 
    initial begin

		#0
		$dumpfile("out.vcd"); 
		$dumpvars(0, myDataCache);
		
		CLK <= 0;
		RESET <= 0;
		readEn <= 0;
		writeEn <=0;

        #1
		readEn <= 0;
		writeEn <=1;
		address <= 8'b0000_000_0;  	// Write the value 10 to cache block 000 (address 0000_000_0)
		dataIn <= 10;		
		
		#200
		readEn <= 0;
		writeEn <=1;
		address <= 8'b0000_001_1; 	// Write the value 30 to cache block 001 (address 0000_001_1)
        dataIn <= 30;
  
		#400
		readEn <= 1;
		writeEn <=0;
		address <= 8'b0000_000_0; 	// Read the value from cache block 000 (address 0000_000_0)

		#600
		readEn <= 0;
		writeEn <=1;
		address <= 8'b0001_000_0; 	// Write the value 10 to cache block 000 (address 0001_000_0), write back before read
        dataIn <= 40;		
		
		#800
		readEn <= 1;
		writeEn <=0;
		address <= 8'b0000_000_0; 	// Read the value from cache block 000 (address 0000_000_0)
   		
		#1000
		readEn <= 1;
		writeEn <=0;
		address <= 8'b0000_001_1; 	// Read the value from cache block 001 (address 0000_001_1) 
   		
		#1200
		readEn <= 1;
		writeEn <=0;
		address <= 8'b0001_000_0;	// Read the value from cache block 000 (address 0001_000_0)
   		
		#2000
        $finish;
    end
    
    always begin
		#5 CLK=~CLK;
	end
	
endmodule


module dataCache(
	CLK,RESET,
	readEn,writeEn,		// read and write enable data bits 
	address,			// read/write address
	dataIn,				// write the data to cache from processor
	dataOut,			// send data to the processor
	busy);				// wait flag

	output busy;					// BUSY signal to the processor
	wire busyMem;					// busy state of MainMemory
	reg busyCache;					// busy state of DataCache

	or(busy, busyMem, busyCache);

	input       CLK, RESET;	
	input		readEn,writeEn;		// cache read/write control signals
	input[7:0]  address;			// address selector
	input[7:0]	dataIn;				// data input bus
	output[7:0] dataOut;			// data output bus

	reg[7:0] dataOut;				// processor <---- cache
	
	// -- Cache <-> DataMemory  ----------------------------------------------

	wire [15:0] dataFromM;			// cache <-------- data memory 
	reg [15:0] dataToM;				// data memory <-- cache 
	
	reg writeM, readM;				// data memory read/write control signals
	reg[6:0] dataMAddress;			// data block address (7bits)

	// data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);
	data_mem myMem(CLK, RESET, readM, writeM, dataMAddress, dataFromM, dataToM, busyMem);
	

	// -- Cache special variables ---------------------------------------------
	reg [3:0] TAG [7:0];			// 8 tags of 4bits
	reg V [7:0];					// 8 valid bits
	reg D [7:0];					// 8 dirty bits
	
	reg [15:0] cacheData[7:0];		// 8 blocks of 2 byte data 
	
	wire offset;
	wire[3:0] tag;
	wire[2:0] index;
	
	integer i;	

	assign offset = address[0];
	assign tag = address[7:4];
	assign index = address[3:1];

	// Reset the cache data on RST = 1 
	always @(posedge RESET) begin
		for (i=0;i<8; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 4'bxxxx;
			V[i] <= 1'b0;
			D[i] <= 1'b0;
		end
		busyCache <= 1'b0;
		//$display("\tRESET\n");
	end
	
	initial begin
		for (i=0;i<8; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 1'b0;
			V[i] <= 1'b0;
			D[i] <= 1'b0;
			
		end
		busyCache <= 1'b0;
		writeM <=0;
		readM <=0;
		dataMAddress <=0;
		dataToM <=0;

		//$display("\tRESET\n");
	end
	
	always @(address or posedge writeEn) begin	

		// --- READ ----------
		if(!writeEn && readEn && !busyCache) begin				
			busyCache <= 1'b1;
			
			if (TAG[index]==tag && V[index]==1'b1) begin
					// tag match and valid ---> HIT, 			no need any process
				$display("\t HIT on the [ %b | %b | %b ] V[%b] D[%b] \t <tag match, valid, r>", tag, index, offset, V[index], D[index]);
					
			end else begin
					// tag isn't match --> Conflict MISS
					
				if (D[index]==1) begin
					// Write back the current block into the data memory
					$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, dirty, r>", tag, index, offset, V[index], D[index]);
					
							
					// --- Write-----------------

					writeM <= 1;
					readM <= 0;

					#10
					dataMAddress <= address[7:1];

					#10
					dataToM <= cacheData[index];
							
					#2
					writeM <=0;
					readM <=0;
							
							// ---------------------------
							
					D[index] <= 1'b0;
							
				end else begin
					$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, r>", tag, index, offset, V[index], D[index]);
				end
						
						// --- Read -----------------
				#1000

				writeM <=0;
				readM <=1;

				#10
				dataMAddress <= address[7:1];
						
				#10
				cacheData[index] <= dataFromM;

				#2 
				writeM <=0;
				readM <=0;

				// ---------------------------
						
				TAG[index] <= tag;	
				V[index] <= 1'b1;
					
			end
				
			#10
			// -- Send the required data to the processor ----
			if (offset == 0) begin
				dataOut <= cacheData[index][7:0];
			end else begin
				dataOut <= cacheData[index][15:8];
			end

			// ------------------------------------------------------------
			
			#10
			$display("Read %d from  %d", dataOut, index);
			busyCache <= 1'b0;
		end
		
		// ----- WRITE --------
		if (writeEn && !readEn && !busyCache) begin
			busyCache <= 1'b1;
				
			if (TAG[index]==tag && V[index]==1) begin
				// tag match and valid ---> HIT, 			just write
				#10
				$display("\t HIT on the [ %b | %b | %b ] V[%b] D[%b] \t <tag match, valid, w>", tag, index, address[0],  V[index], D[index]);
				D[index] <= 1'b1;
			
			end else begin
			// tag not match ---> MISS			
					
				if (D[index]==1) begin
					// Write back the current block into the data memory
					$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, w>", tag, index,  address[0], V[index], D[index]);

					// --- Write  Back ------		
					writeM <=1;
					readM <=0;
						
					#10
					dataMAddress <= address[7:1];
					dataToM <= cacheData[index];
						
					#10				
					writeM <=0;
					readM <=0;
					// ---------------------------
						
				end else begin
					$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, w>", tag, index,  address[0], V[index], D[index]);
				end

			end
				
			// write the data into cache and make dirty bit=1, valid bit=1
			#10
			cacheData[index] <= dataIn;
			TAG[index] <= tag;
			D[index] <= 1'b1;
			V[index] <= 1'b1;
				
			#10
			$display("Written %d into  %d", dataIn, index);
			busyCache <= 1'b0;
		end

		$display("");
	end
endmodule


module data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);

	input           CLK, RESET, readEn, writeEn;
	input [6:0] 	dataAddress;
	input [15:0] 	dataIn;
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

	always @(CLK) begin
			// Reading data from the memory
			if(!writeEn && readEn && !BUSY) begin
				BUSY <= 1;

				#1000;  // Clock * Cycles
				dataOut <= (memory[dataAddress*2 + 0 ]*256) + memory[dataAddress*2 + 1];
				$display ("MEM: Read %d from  %d", dataOut, dataAddress);
				BUSY <= 0;
				
			end

			if (writeEn && !readEn && !BUSY) begin
				BUSY <= 1;

				#1000;  // Clock * Cycles
				memory[dataAddress*2 + 0 ] <= dataIn[15:8];
				memory[dataAddress*2 + 1 ] <= dataIn[7:0];
				
				$display ("MEM: Written %d into  %d", dataIn, dataAddress);
				BUSY <= 0;
					
			end
	end
	
	initial begin
	    // Initialize the memory
       	BUSY<= 0;
		for (i=0;i<256; i=i+1)
			memory[i] <= 0;
	end
endmodule



